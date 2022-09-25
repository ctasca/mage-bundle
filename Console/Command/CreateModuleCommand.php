<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Api\MakerModuleInterface;

class CreateModuleCommand extends Command
{
    private MakerModuleInterface $makerModule;

    /**
     * @param MakerModuleInterface $makerModule
     */
    public function __construct(
        MakerModuleInterface $makerModule
    ) {
        $this->makerModule = $makerModule;
        parent::__construct();
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
        $this->makerModule->make($input, $output);
    }
}