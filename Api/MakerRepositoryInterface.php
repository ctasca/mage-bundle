<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerRepositoryInterface extends MakerInterface
{
    public const REPOSITORY_TEMPLATES_DIR = 'repository';
    public const REPOSITORY_DATA_INTERFACE_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'data-interface';
    public const REPOSITORY_INTERFACE_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'repository-interface';
    public const REPOSITORY_SEARCH_RESULT_INTERFACE_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'repository-search-result-interface';
    public const REPOSITORY_MODEL_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'repository-model';
    public const REPOSITORY_SEARCH_RESULT_MODEL_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'search-result-model';
}
