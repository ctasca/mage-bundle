<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Ctasca\MageBundle\Api\MakerLoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateLoggerCommand extends Command
{
    private MakerLoggerInterface $maker;

    /**
     * @param \Ctasca\MageBundle\Api\MakerLoggerInterface $maker
     */
    public function __construct(
        MakerLoggerInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('magebundle:logger:create')
            ->setDescription('Creates a Logger Handler and Logger classes files in specified Company/Module');

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
