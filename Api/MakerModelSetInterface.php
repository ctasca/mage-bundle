<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerModelSetInterface extends MakerInterface
{
    public const MODEL_TEMPLATES_DIR = 'model-set' . DIRECTORY_SEPARATOR . 'model';
    public const RESOURCE_MODEL_TEMPLATES_DIR = 'model-set' . DIRECTORY_SEPARATOR . 'resource-model';
    public const COLLECTION_MODEL_TEMPLATES_DIR = 'model-set' . DIRECTORY_SEPARATOR . 'collection';
}
