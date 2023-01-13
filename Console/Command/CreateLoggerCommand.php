<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Api\MakerLoggerInterface;

class CreateLoggerCommand extends Command
{
    private MakerLoggerInterface $maker;

    /**
     * @param MakerLoggerInterface $maker
     */
    public function __construct(
        MakerLoggerInterface $maker
    ) {
        $this->maker = $maker;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:logger:create')
            ->setDescription('Creates a Logger Handler and Logger classes files in specified Company/Module');

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
