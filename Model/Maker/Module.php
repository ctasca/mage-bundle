<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerModuleInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Module extends AbstractMaker implements MakerModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $question = $this->questionFactory->create('Enter Company Name');
        QuestionValidator::validateUcFirst(
            $question,
            "Company Name is required and must start with an uppercase character",
            self::MAX_QUESTION_ATTEMPTS
        );
        $companyName = $this->questionHelper->ask($input, $output, $question);
        $question = $this->questionFactory->create('Enter Module Name');
        QuestionValidator::validateUcFirst(
            $question,
            "Module Name is required and must start with an uppercase character",
            self::MAX_QUESTION_ATTEMPTS
        );
        $moduleName = $this->questionHelper->ask($input, $output, $question);
        try {
            $module = $companyName . '_' . $moduleName;
            /** @var \Ctasca\MageBundle\Model\App\Code\Locator $appCodeLocator */
            $appCodeLocator = $this->appCodeLocatorFactory->create(
                ['dirname' => $this->makeModulePathFromName($module)]
            );
            $moduleDirectory = $appCodeLocator->locate();
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            list($templateLocator, $registrationTemplateDirectory) = $this->locateTemplateDirectory(
                'module',
                self::REGISTRATION_TEMPLATE_FILENAME
            );
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModule($module);
            // write registration.php file
            $registrationTemplate = $this->getTemplateContent($templateLocator, $registrationTemplateDirectory);
            $registration = $this->makeFile($dataProvider, $registrationTemplate);
            $this->writeFile($appCodeLocator, $moduleDirectory, 'registration.php', $registration);
            // write module.xml file from choice. Note: no need to provide a filename as it will be picked up from
            // the template filename
            $this->writeFileFromTemplateChoice(
                $this->makeModulePathFromName($module) . DIRECTORY_SEPARATOR . 'etc',
                $input,
                $output,
                self::MODULE_XML_TEMPLATES_DIR,
                $dataProvider
            );
            $output->writeln(
                sprintf(
                    '<info>Completed! Module successfully created in app/code/%s/%s</info>',
                    $companyName,
                    $moduleName
                )
            );
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logAndOutputErrorMessage($e, $output);
        }
    }
}
