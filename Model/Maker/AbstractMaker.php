<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Maker;

use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Ctasca\MageBundle\Model\App\Code\LocatorFactory as AppCodeLocatorFactory;
use Ctasca\MageBundle\Model\Template\LocatorFactory as TemplateLocatorFactory;
use Ctasca\MageBundle\Model\Template\DataProviderFactory;
use Ctasca\MageBundle\Model\File\MakerFactory as FileMakerFactory;
use Ctasca\MageBundle\Logger\Logger;

abstract class AbstractMaker
{
    protected SymfonyQuestionHelper $questionHelper;
    protected AppCodeLocatorFactory $appCodeLocatorFactory;
    protected TemplateLocatorFactory $templateLocatorFactory;
    protected DataProviderFactory $dataProviderFactory;
    protected FileMakerFactory $fileMakerFactory;
    protected Logger $logger;

    /**
     * @param SymfonyQuestionHelper $questionHelper
     * @param AppCodeLocatorFactory $appCodeLocatorFactory
     * @param TemplateLocatorFactory $templateLocatorFactory
     * @param DataProviderFactory $dataProviderFactory
     * @param FileMakerFactory $fileMakerFactory
     * @param Logger $logger
     */
    public function __construct(
        SymfonyQuestionHelper $questionHelper,
        AppCodeLocatorFactory $appCodeLocatorFactory,
        TemplateLocatorFactory $templateLocatorFactory,
        DataProviderFactory $dataProviderFactory,
        FileMakerFactory $fileMakerFactory,
        Logger $logger
    ) {
        $this->questionHelper = $questionHelper;
        $this->appCodeLocatorFactory = $appCodeLocatorFactory;
        $this->templateLocatorFactory = $templateLocatorFactory;
        $this->dataProviderFactory = $dataProviderFactory;
        $this->fileMakerFactory = $fileMakerFactory;
        $this->logger = $logger;
    }
}