<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\MakerModuleInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question as CommandQuestion;

class Module extends AbstractMaker  implements MakerModuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function make(InputInterface $input, OutputInterface $output): void
    {
        $helper = $this->questionHelper;
        $question = new CommandQuestion('Enter Company Name');
        QuestionValidator::validate(
            $question,
            "Company Name is required",
            self::MAX_QUESTION_ATTEMPTS
        );
        $companyName = $helper->ask($input, $output, $question);
        $question = new CommandQuestion('Enter Module Name');
        QuestionValidator::validate(
            $question,
            "Module Name is required",
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
            $templateLocator = $this->templateLocatorFactory->create(['dirname' => 'module']);
            $registrationTemplateDirectory = $templateLocator
                ->setTemplateFilename(self::REGISTRATION_TEMPLATE_FILENAME)
                ->locate();
            $registrationTemplate = $templateLocator
                ->getRead($registrationTemplateDirectory)
                ->readFile($templateLocator->getTemplateFilename());
            $moduleTemplateDirectory = $templateLocator
                ->setTemplateFilename('etc' . DIRECTORY_SEPARATOR . self::MODULE_XML_TEMPLATE_FILENAME)
                ->locate();
            $moduleXmlTemplate = $templateLocator
                ->getRead($moduleTemplateDirectory)
                ->readFile($templateLocator->getTemplateFilename());
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModule($module);
            $registrationMaker = $this->fileMakerFactory->create($dataProvider, $registrationTemplate);
            $registration = $registrationMaker->make();
            $moduleXmlMaker = $this->fileMakerFactory->create($dataProvider, $moduleXmlTemplate);
            $moduleXml = $moduleXmlMaker->make();
            $writer = $appCodeLocator->getWrite($moduleDirectory);
            $writer->writeFile('registration.php', $registration);
            $writer->writeFile('etc' . DIRECTORY_SEPARATOR . 'module.xml', $moduleXml);
            $output->writeln('Completed!');
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
            $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
        }
    }
}