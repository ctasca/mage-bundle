<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Ctasca\MageBundle\Model\App\Code\LocatorFactory as AppCodeLocatorFactory;
use Ctasca\MageBundle\Model\Template\LocatorFactory as TemplateLocatorFactory;

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

    private LocatorFactory $appCodeLocatorFactory;
    private TemplateLocatorFactory $templateLocatorFactory;

    /**
     * @param AppCodeLocatorFactory $appCodeLocatorFactory
     * @param TemplateLocatorFactory $templateLocatorFactory
     */
    public function __construct(
        AppCodeLocatorFactory $appCodeLocatorFactory,
        TemplateLocatorFactory $templateLocatorFactory
    ) {
        parent::__construct();
        $this->appCodeLocatorFactory = $appCodeLocatorFactory;
        $this->templateLocatorFactory = $templateLocatorFactory;
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
        /** @var \Ctasca\MageBundle\Model\App\Code\Locator $appCodeLocator */
        $appCodeLocator = $this->appCodeLocatorFactory->create(['dirname' => $companyName . DIRECTORY_SEPARATOR . $moduleName]);
        try {
            $locatedDirectory = $appCodeLocator->locate();
            /** @var \Ctasca\MageBundle\Model\Template\Locator $templateLocator */
            $templateLocator = $this->templateLocatorFactory->create(['dirname' => 'module']);
            $registrationTemplate = $templateLocator
                ->setTemplateFilename(self::REGISTRATION_TEMPLATE_FILENAME)
                ->locate();
            $output->writeln($registrationTemplate);
        } catch (\Exception $e) {
            $output->writeln("<error>Something went wrong</error>");
        }
    }
}