<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\LocatorInterface;
use Ctasca\MageBundle\Api\MakerInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Ctasca\MageBundle\Model\Template\DataProvider;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Ctasca\MageBundle\Model\App\Code\LocatorFactory as AppCodeLocatorFactory;
use Ctasca\MageBundle\Model\Template\LocatorFactory as TemplateLocatorFactory;
use Ctasca\MageBundle\Model\Template\DataProviderFactory;
use Ctasca\MageBundle\Model\Template\CustomData\LocatorFactory as CustomDataLocatorFactory;
use Ctasca\MageBundle\Model\File\MakerFactory as FileMakerFactory;
use Ctasca\MageBundle\Logger\Logger;

abstract class AbstractMaker implements MakerInterface
{
    protected SymfonyQuestionHelper $questionHelper;
    protected AppCodeLocatorFactory $appCodeLocatorFactory;
    protected TemplateLocatorFactory $templateLocatorFactory;
    protected DataProviderFactory $dataProviderFactory;
    protected CustomDataLocatorFactory $customDataLocatorFactory;
    protected FileMakerFactory $fileMakerFactory;
    protected Logger $logger;

    /**
     * @param SymfonyQuestionHelper $questionHelper
     * @param AppCodeLocatorFactory $appCodeLocatorFactory
     * @param TemplateLocatorFactory $templateLocatorFactory
     * @param DataProviderFactory $dataProviderFactory
     * @param CustomDataLocatorFactory $customDataLocatorFactory
     * @param FileMakerFactory $fileMakerFactory
     * @param Logger $logger
     */
    public function __construct(
        SymfonyQuestionHelper $questionHelper,
        AppCodeLocatorFactory $appCodeLocatorFactory,
        TemplateLocatorFactory $templateLocatorFactory,
        DataProviderFactory $dataProviderFactory,
        CustomDataLocatorFactory $customDataLocatorFactory,
        FileMakerFactory $fileMakerFactory,
        Logger $logger
    ) {
        $this->questionHelper = $questionHelper;
        $this->appCodeLocatorFactory = $appCodeLocatorFactory;
        $this->templateLocatorFactory = $templateLocatorFactory;
        $this->dataProviderFactory = $dataProviderFactory;
        $this->customDataLocatorFactory = $customDataLocatorFactory;
        $this->fileMakerFactory = $fileMakerFactory;
        $this->logger = $logger;
    }

    /**
     * @param string $moduleName
     * @return string
     */
    protected function makeModulePathFromName(string $moduleName): string
    {
        return str_replace('_', DIRECTORY_SEPARATOR, $moduleName);
    }

    /**
     * @param array $a
     * @return string
     */
    protected function makePathFromArray(array $a): string
    {
        return implode(DIRECTORY_SEPARATOR, $a);
    }

    /**
     * @param string $locatorDirectory
     * @return LocatorInterface
     */
    protected function getAppCodeLocator(string $locatorDirectory): LocatorInterface
    {
        return $this->appCodeLocatorFactory->create(
            ['dirname' => $locatorDirectory]
        );
    }

    /**
     * @param string $directory
     * @param string|null $templateFilename
     * @return array
     * @throws \Exception
     */
    protected function locateTemplateDirectory(string $directory, ?string $templateFilename = null): array
    {
        /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
        $templateLocator = $this->templateLocatorFactory->create(['dirname' => $directory]);
        if (!is_null($templateFilename)) {
            return [$templateLocator, $templateLocator->setTemplateFilename($templateFilename)->locate()];
        }
        return [$templateLocator, $templateLocator->locate()];
    }

    /**
     * @param DataProvider $dataProvider
     * @param string $template
     * @return string
     */
    protected function makeFile(DataProvider $dataProvider, string $template): string
    {
        $maker = $this->fileMakerFactory->create($dataProvider, $template);
        return $maker->make();
    }

    /**
     * @param LocatorInterface $locator
     * @param string $directory
     * @param string $filename
     * @param string $bytes
     * @return void
     */
    protected function writeFile(LocatorInterface $locator, string $directory, string $filename, string $bytes): void
    {
        $writer = $locator->getWrite($directory);
        $writer->writeFile($filename, $bytes);
    }

    /**
     * @param LocatorInterface $templateLocator
     * @param string $directory
     * @return string
     */
    protected function getTemplateContent(LocatorInterface $templateLocator, string $directory): string
    {
        return $templateLocator
            ->getRead($directory)
            ->readFile($templateLocator->getTemplateFilename());
    }

    /**
     * @return Question
     */
    protected function makeModuleNameQuestion(): Question
    {
        $question = new Question('Enter Module Name (e.g. Company_Module)');
        QuestionValidator::validateModuleName(
            $question,
            "Module Name is in the wrong format.",
            self::MAX_QUESTION_ATTEMPTS
        );

        return $question;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $templateDirectory
     * @param string|null $templateFilename
     * @return array
     * @throws \Exception
     */
    protected function getTemplateContentFromChoice(
        InputInterface $input,
        OutputInterface $output,
        string $templateDirectory,
        ?string $templateFilename = null
    ): array
    {
        /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
        list($templateLocator, $locatedTemplateDirectory)  =
            $this->locateTemplateDirectory($templateDirectory, $templateFilename);
        $question = new ChoiceQuestion(
            'Please choose a template',
            $templateLocator->getTemplatesChoices()
        );
        $question->setErrorMessage('Chosen template %s is invalid.');
        $template = $this->questionHelper->ask($input, $output, $question);
        $output->writeln('<info>You have selected: '. $template . '</info>');
        $templateLocator->setTemplateFilename($template);
        return [$template, $this->getTemplateContent($templateLocator, $locatedTemplateDirectory)];
    }

    /**
     * @param DataProvider $dataProvider
     * @param string $template
     * @return void
     */
    protected function setDataProviderCustomData(DataProvider $dataProvider, string $template): void
    {
        $this->logger->info(
            __METHOD__ . " initialised template file",
            [
                $template
            ]
        );
        /** @var \Ctasca\MageBundle\Model\Template\CustomData\Locator  $customDataLocatorFactory */
        $customDataLocator = $this->customDataLocatorFactory->create(['dirname' => '']);
        $customDataLocator->setTemplateFilename($template);
        $customData = $customDataLocator->getCustomData();
        $dataProvider->setCustomData($customData);
    }

    /**
     * @param string $path
     * @return array|string|string[]
     */
    protected function makeNamespace(string $path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '\\', $path);
    }

    /**
     * @param \Exception $e
     * @param OutputInterface $output
     * @return void
     */
    protected function logAndOutputErrorMessage(\Exception $e, OutputInterface $output): void
    {
        $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
        $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
    }
}