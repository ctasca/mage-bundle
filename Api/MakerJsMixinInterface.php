<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerJsMixinInterface extends MakerInterface
{
    const JS_MIXIN_TEMPLATES_DIR =  'js' . DIRECTORY_SEPARATOR . 'mixin';
}
