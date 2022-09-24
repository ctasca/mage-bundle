<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Ctasca\MageBundle\Console\Question\Prompt\Validator as QuestionValidator;
use Ctasca\MageBundle\Model\App\Code\LocatorFactory;

class CreateModuleCommand extends Command
{
    /**
     * Max Attempts for prompt questions
     */
    const MAX_QUESTION_ATTEMPTS = 2;

    private LocatorFactory $locatorFactory;

    /**
     * @param LocatorFactory $locatorFactory
     */
    public function __construct(
        LocatorFactory $locatorFactory
    ) {
        parent::__construct();
        $this->locatorFactory = $locatorFactory;
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
        /** @var \Ctasca\MageBundle\Model\App\Code\Locator $locator */
        $locator = $this->locatorFactory->create(['moduleName' => $companyName . DIRECTORY_SEPARATOR . $moduleName]);
        $locatedDirectory = $locator->locate();
        $output->writeln($locatedDirectory);
    }
}