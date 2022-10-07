<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ctasca\MageBundle\Api\MakerModelSetInterface;

class CreateModelSetCommand extends Command
{
    private MakerModelSetInterface $makerModelSet;

    /**
     * @param MakerModelSetInterface $makerModelSet
     */
    public function __construct(
        MakerModelSetInterface $makerModelSet
    ) {
        $this->makerModelSet = $makerModelSet;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setName('magebundle:model:set:create')
            ->setDescription('Creates a Model, Resource Model and Collection classes in specified Company/Module');

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->makerModelSet->make($input, $output);
    }
}