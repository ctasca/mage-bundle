<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Ctasca\MageBundle\Api\LocatorInterface;
use Ctasca\MageBundle\Api\MakerInterface;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Ctasca\MageBundle\Model\Template\DataProvider;
use Symfony\Component\Console\Question\Question;
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
        if (strpos('.xml', $template) > 0) {
            $template = str_replace('xml', 'php', $template);
            $this->logger->info(
                __METHOD__ . " replaced template file",
                [
                    $template
                ]
            );
        }
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
}