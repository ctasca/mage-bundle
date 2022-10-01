<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Api\MakerEtcXmlInterface;

class CreateEtcXmlCommand extends Command
{
    private MakerEtcXmlInterface $makerEtcXml;

    /**
     * @param MakerEtcXmlInterface $makerEtcXml
     */
    public function __construct(
        MakerEtcXmlInterface $makerEtcXml
    ) {
        $this->makerEtcXml = $makerEtcXml;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:etc:xml:create')
            ->setDescription('Creates an xml file in specified Company/Module etc/ directory');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->makerEtcXml->make($input, $output);
    }
}