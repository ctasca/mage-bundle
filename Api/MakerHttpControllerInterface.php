<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerHttpControllerInterface extends MakerInterface
{
    const ADMINHTML_CONTROLLER_TEMPLATES_DIR = 'adminhtml-http-controller';
    const STANDARD_CONTROLLER_TEMPLATES_DIR = 'http-controller';
}