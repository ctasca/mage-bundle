<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface LocatorInterface
{
    /**
     * Locate a directory within a Magento application
     *
     * @return string
     */
    public function locate(): string;
}