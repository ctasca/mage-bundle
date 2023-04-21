<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


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
     * @param \Ctasca\MageBundle\Api\MakerConsoleCommandInterface $maker
     */
    public function __construct(
        MakerConsoleCommandInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('magebundle:console-command:create')
            ->setDescription('Creates a Console Command class in specified Company/Module');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->maker->make($input, $output);
    }
}
