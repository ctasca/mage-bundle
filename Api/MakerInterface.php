<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface MakerInterface
{
    /**
     * Max Attempts for commands questions
     */
    const MAX_QUESTION_ATTEMPTS = 3;

    /**
     * Pattern to match extended templates which set the filename as the template filename.
     * For example Company/Module/etc xml files created with magebundle:etc:xml:create command.
     *
     * This allows to set custom xml templates for specific areas.
     */
    const CUSTOM_TEMPLATE_PATTERN_MATCH = '/[_]{2}\w{0,}/';

    /**
     * Make magebundle bin/magento commands logic
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function make(InputInterface $input, OutputInterface $output): void;
}
