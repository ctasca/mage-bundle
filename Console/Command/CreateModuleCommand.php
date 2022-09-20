<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Magento\Framework\App\State as AppState;
use Ctasca\MageBundle\Model\App\Code\LocatorFactory;

class CreateModuleCommand extends Command
{
    /**
     * @var AppState
     */
    private $appState;

    /**
     * @var LocatorFactory
     */
    private $locatorFactory;

    /**
     * @param AppState $appState
     */
    public function __construct(
        AppState $appState,
        LocatorFactory $locatorFactory
    ) {
        parent::__construct();
        $this->appState = $appState;
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
        $prompt = new Question('Enter Company Name: ');
        $prompt->setMaxAttempts(2);
        $companyName = $helper->ask($input, $output, $prompt);
        $prompt = new Question('Enter Module Name: ');
        $prompt->setMaxAttempts(2);
        $moduleName = $helper->ask($input, $output, $prompt);
        /** @var \Ctasca\MageBundle\Model\App\Code\Locator $locator */
        $locator = $this->locatorFactory->create(['moduleName' => $companyName . DIRECTORY_SEPARATOR . $moduleName]);
        $locatedDirectory = $locator->locate();
        $output->writeln($locatedDirectory);
    }
}