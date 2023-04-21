<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Ctasca\MageBundle\Api\MakerHelperInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateHelperCommand extends Command
{
    private MakerHelperInterface $maker;

    /**
     * @param \Ctasca\MageBundle\Api\MakerHelperInterface $maker
     */
    public function __construct(
        MakerHelperInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('magebundle:helper:create')
            ->setDescription('Creates a Helper class in specified Company/Module');

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
