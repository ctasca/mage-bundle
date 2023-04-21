<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Ctasca\MageBundle\Api\MakerEtcXmlInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateEtcXmlCommand extends Command
{
    private MakerEtcXmlInterface $maker;

    /**
     * @param \Ctasca\MageBundle\Api\MakerEtcXmlInterface $maker
     */
    public function __construct(
        MakerEtcXmlInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('magebundle:etc:xml:create')
            ->setDescription('Creates an xml file in specified Company/Module etc/ directory');

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
