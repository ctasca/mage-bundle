<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface FileMakerInterface
{
    /**
     * Pattern to for preg_match_all function
     */
    public const DATA_PLACEHOLDER_PATTERN = '/{{([A-Za-z_]+)}}/';

    /**
     * Replace placeholders with data provider data
     *
     * @return string
     */
    public function make(): string;
}
