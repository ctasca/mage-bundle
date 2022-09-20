<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Magento\Framework\App\State as AppState;

class CreateModuleCommand extends Command
{
    /**
     * @var AppState
     */
    private $appState;

    /**
     * @param AppState $appState
     */
    public function __construct(
        AppState $appState
    ) {
        parent::__construct();
        $this->appState = $appState;
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
        $companyName = $helper->ask($input, $output, $prompt);
        $prompt = new Question('Enter Module Name: ');
        $moduleName = $helper->ask($input, $output, $prompt);
        $output->writeln($companyName . ' ' . $moduleName);
    }
}