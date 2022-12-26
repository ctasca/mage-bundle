<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerConsoleCommandInterface extends MakerInterface
{
    const CONSOLE_COMMAND_TEMPLATES_DIR = 'console-command';
}