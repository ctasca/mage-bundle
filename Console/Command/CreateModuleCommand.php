<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\ProgressBar;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Ctasca\MageBundle\Model\App\Code\LocatorFactory as AppCodeLocatorFactory;
use Ctasca\MageBundle\Model\Template\LocatorFactory as TemplateLocatorFactory;
use Ctasca\MageBundle\Model\Template\DataProviderFactory;
use Ctasca\MageBundle\Model\File\MakerFactory;
use Ctasca\MageBundle\Logger\Logger;

class CreateModuleCommand extends Command
{
    /**
     * Max Attempts for prompt questions
     */
    const MAX_QUESTION_ATTEMPTS = 2;

    /**
     * registration.php file template
     */
    const REGISTRATION_TEMPLATE_FILENAME = 'registration.tpl.php';

    /**
     * module.xml file template
     */
    const MODULE_XML_TEMPLATE_FILENAME = 'module.tpl.xml';

    private AppCodeLocatorFactory $appCodeLocatorFactory;
    private TemplateLocatorFactory $templateLocatorFactory;
    private DataProviderFactory $dataProviderFactory;
    private MakerFactory $makerFactory;
    private Logger $logger;

    /**
     * @param AppCodeLocatorFactory $appCodeLocatorFactory
     * @param TemplateLocatorFactory $templateLocatorFactory
     * @param DataProviderFactory $dataProviderFactory
     * @param MakerFactory $makerFactory
     * @param Logger $logger
     */
    public function __construct(
        AppCodeLocatorFactory $appCodeLocatorFactory,
        TemplateLocatorFactory $templateLocatorFactory,
        DataProviderFactory $dataProviderFactory,
        MakerFactory $makerFactory,
        Logger $logger
    ) {
        parent::__construct();
        $this->appCodeLocatorFactory = $appCodeLocatorFactory;
        $this->templateLocatorFactory = $templateLocatorFactory;
        $this->dataProviderFactory = $dataProviderFactory;
        $this->makerFactory = $makerFactory;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:module:create')
            ->setDescription('Creates a Magento module skeleton in app/code');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('Enter Company Name: ');
        QuestionValidator::validate($question, "Company Name is required", self::MAX_QUESTION_ATTEMPTS);
        $companyName = $helper->ask($input, $output, $question);
        $question = new Question('Enter Module Name: ');
        QuestionValidator::validate($question, "Module Name is required", self::MAX_QUESTION_ATTEMPTS);
        $moduleName = $helper->ask($input, $output, $question);
        try {
            /** @var \Ctasca\MageBundle\Model\App\Code\Locator $appCodeLocator */
            $module = $companyName . '_' . $moduleName;
            $appCodeLocator = $this->appCodeLocatorFactory->create(['dirname' => $companyName . DIRECTORY_SEPARATOR . $moduleName]);
            $progressBar = new ProgressBar($output, 3);
            $progressBar->setFormat(
                "<fg=white;bg=cyan> %status:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n?  %estimated:-20s%  %memory:20s%"
            );
            $progressBar->setMessage("Starting...", 'status');
            $progressBar->start();
            $appCodeDirectory = $appCodeLocator->locate();
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            $templateLocator = $this->templateLocatorFactory->create(['dirname' => 'module']);
            $registrationTemplateDirectory = $templateLocator
                ->setTemplateFilename(self::REGISTRATION_TEMPLATE_FILENAME)
                ->locate();
            $registrationTemplate = $templateLocator->getRead($registrationTemplateDirectory)->readFile($templateLocator->getTemplateFilename());
            $progressBar->advance();
            $moduleTemplateDirectory = $templateLocator
                ->setTemplateFilename('etc' . DIRECTORY_SEPARATOR . self::MODULE_XML_TEMPLATE_FILENAME)
                ->locate();
            $moduleXmlTemplate = $templateLocator->getRead($moduleTemplateDirectory)->readFile($templateLocator->getTemplateFilename());
            $progressBar->advance();
            /** @var \Ctasca\MageBundle\Model\Template\DataProvider  $dataProvider */
            $dataProvider = $this->dataProviderFactory->create();
            $dataProvider->setPhp('<?php');
            $dataProvider->setModule($module);
            $registrationMaker = $this->makerFactory->create($dataProvider, $registrationTemplate);
            $registration = $registrationMaker->make();
            $moduleXmlMaker = $this->makerFactory->create($dataProvider, $moduleXmlTemplate);
            $moduleXml = $moduleXmlMaker->make();
            $output->writeln($registration);
            $output->writeln($moduleXml);
            $progressBar->setMessage("Finished", 'status');
            $progressBar->finish();
            $output->writeln('');
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__ . " Exception in command:", [$e->getMessage()]);
            $output->writeln("<error>Something went wrong! Check the mage-bundle.log if logging is enabled.</error>");
        }
    }
}