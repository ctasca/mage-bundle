<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Api\MakerObserverInterface;

class CreateObserverCommand extends Command
{
    private MakerObserverInterface $maker;

    /**
     * @param MakerObserverInterface $maker
     */
    public function __construct(
        MakerObserverInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:observer:create')
            ->setDescription('Creates an Observer class in specified Company/Module');

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