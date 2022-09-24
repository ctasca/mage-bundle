<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

interface LocatorInterface
{
    /**
     * Code directory name (within app directory)
     */
    const CODE_DIR = 'code/';

    /**
     * Directory name to be created in pub/media directory
     */
    const PUB_MEDIA_MAGE_BUNDLE_DIRNAME = 'mage-bundle/';

    /**
     * Bundle/Skeleton directory relative path
     */
    const VENDOR_SKELETON_PATH_DIR = 'vendor/ctasca/mage-bundle/Bundle/Skeleton/';

    /**
     * Locate a directory within a Magento application
     *
     * @return string
     */
    public function locate(): string;
}