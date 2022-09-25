<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Api\MakerHttpGetControllerInterface;

class CreateControllerCommand extends Command
{
    private MakerHttpGetControllerInterface $makerHttpGetController;

    /**
     * @param MakerHttpGetControllerInterface $makerHttpGetController
     */
    public function __construct(
        MakerHttpGetControllerInterface $makerHttpGetController
    ) {
        $this->makerHttpGetController = $makerHttpGetController;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:controller:create')
            ->setDescription('Creates a Controller action in specified Company/Module');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->makerHttpGetController->make($input, $output);
    }
}