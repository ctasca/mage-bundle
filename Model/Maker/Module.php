<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerModuleInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question as CommandQuestion;
use Symfony\Component\Console\Question\ChoiceQuestion;

class Module extends AbstractMaker implements MakerModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->questionHelper;
        $question = new CommandQuestion('Enter Company Name');
        QuestionValidator::validateUcFirst(
            $question,
            "Company Name is required and must start with an uppercase character",
            self::MAX_QUESTION_ATTEMPTS
        );
        $companyName = $helper->ask($input, $output, $question);
        $question = new CommandQuestion('Enter Module Name');
        QuestionValidator::validateUcFirst(
            $question,
            "Module Name is required and must start with an uppercase character",
            self::MAX_QUESTION_ATTEMPTS
        );
        $moduleName = $helper->ask($input, $output, $question);
        try {
            $module = $companyName . '_' . $moduleName;
            /** @var \Ctasca\MageBundle\Model\App\Code\Locator $appCodeLocator */
            $appCodeLocator = $this->appCodeLocatorFactory->create(
                ['dirname' => $companyName . DIRECTORY_SEPARATOR . $moduleName]
            );
            $moduleDirectory = $appCodeLocator->locate();
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator, $registrationTemplateDirectory) = $this->locateTemplateDirectory(
                'module',
                self::REGISTRATION_TEMPLATE_FILENAME
            );
            $registrationTemplate = $this->getTemplateContent($templateLocator, $registrationTemplateDirectory);
            list($moduleXmlTemplateLocator, $moduleTemplateDirectory) = $this->locateTemplateDirectory('module/etc');
            $question = new ChoiceQuestion(
                sprintf('Please choose the module.xml template to use for the %s_%s module', $companyName, $moduleName),
                $moduleXmlTemplateLocator->getTemplatesChoices()
            );
            $question->setErrorMessage('Chosen template %s is invalid.');
            $template = $helper->ask($input, $output, $question);
            $output->writeln('You have selected: '. $template);
            $templateLocator->setTemplateFilename($template);
            $moduleXmlTemplate = $this->getTemplateContent($templateLocator, $moduleTemplateDirectory);
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModule($module);
            $this->setDataProviderCustomData($dataProvider, 'module.tpl.php');
            $registration = $this->makeFile($dataProvider, $registrationTemplate);
            $moduleXml = $this->makeFile($dataProvider, $moduleXmlTemplate);
            $this->writeFile($appCodeLocator, $moduleDirectory, 'registration.php', $registration);
            $this->writeFile($appCodeLocator, $moduleDirectory, 'etc' . DIRECTORY_SEPARATOR . 'module.xml', $moduleXml);
            $output->writeln(
                sprintf('Completed! Module successfully created in app/code/%s/%s', $companyName, $moduleName)
            );
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
            $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
        }
    }
}