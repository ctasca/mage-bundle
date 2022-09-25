<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface MakerInterface
{
    /**
     * Make magebundle bin/magento commands logic
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function make(InputInterface $input, OutputInterface $output): void;
}