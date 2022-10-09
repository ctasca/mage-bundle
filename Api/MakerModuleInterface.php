<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerModuleInterface extends MakerInterface
{
    const REGISTRATION_TEMPLATE_FILENAME = 'registration.tpl.php';
    const MODULE_XML_TEMPLATES_DIR = 'module' . DIRECTORY_SEPARATOR . 'etc';
}