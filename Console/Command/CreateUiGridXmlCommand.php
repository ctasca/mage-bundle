<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Ctasca\MageBundle\Api\MakerUiComponentXmlInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUiGridXmlCommand extends Command
{
    protected MakerUiComponentXmlInterface $maker;

    /**
     * @param MakerUiComponentXmlInterface $maker
     */
    public function __construct(
        MakerUiComponentXmlInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:ui-grid-xml:create')
            ->setDescription('Creates an xml for ui component grid');

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
