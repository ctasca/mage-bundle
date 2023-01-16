<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface MakerRepositoryInterface extends MakerInterface
{
    const REPOSITORY_TEMPLATES_DIR = 'repository';
    const REPOSITORY_DATA_INTERFACE_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'data-interface';
    const REPOSITORY_INTERFACE_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'repository-interface';
    const REPOSITORY_SEARCH_RESULT_INTERFACE_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'repository-search-result-interface';
    const REPOSITORY_MODEL_TEMPLATES_DIR =
        self::REPOSITORY_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'repository-model';
}
