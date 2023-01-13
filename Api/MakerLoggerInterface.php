<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerLoggerInterface extends MakerInterface
{
    const LOGGER_TEMPLATES_DIR = 'logger';
    const LOGGER_HANDLER_TEMPLATES_DIR = self::LOGGER_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'handler';
    const LOGGER_LOGGER_TEMPLATES_DIR = self::LOGGER_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'logger';
}
