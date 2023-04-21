<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerUiComponentInterface extends MakerInterface
{
    public const UI_COMPONENT_TEMPLATES_DIR = 'js' . DIRECTORY_SEPARATOR . 'ui-component';
}
