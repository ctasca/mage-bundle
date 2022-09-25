<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerModuleInterface extends MakerInterface
{
    /**
     * registration.php file template
     */
    const REGISTRATION_TEMPLATE_FILENAME = 'registration.tpl.php';

    /**
     * module.xml file template
     */
    const MODULE_XML_TEMPLATE_FILENAME = 'module.tpl.xml';
}