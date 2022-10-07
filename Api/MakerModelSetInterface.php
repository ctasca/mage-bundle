<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerModelSetInterface extends MakerInterface
{
    const MODEL_TEMPLATE_DIR = 'model-set' . DIRECTORY_SEPARATOR .  'model';
    const RESOURCE_MODEL_TEMPLATE_DIR = 'model-set' . DIRECTORY_SEPARATOR . 'resource-model';
    const COLLECTION_MODEL_TEMPLATE_DIR = 'model-set' . DIRECTORY_SEPARATOR . 'collection';
}