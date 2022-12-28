<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Ctasca\MageBundle\Api\MakerConsoleCommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateConsoleCommandCommand extends Command
{
    private MakerConsoleCommandInterface $maker;

    /**
     * @param MakerConsoleCommandInterface $maker
     */
    public function __construct(
        MakerConsoleCommandInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:console-command:create')
            ->setDescription('Creates a Console Command class in specified Company/Module');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->maker->make($input, $output);
    }
}
