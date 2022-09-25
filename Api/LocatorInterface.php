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
     * Directory where custom data for templates should be located.
     * To provide custom data for templates, name the file exactly as the template
     * (with .php extension if it is an XML template)
     * returning an array of setters methods and corresponding
     * values that will be added to the DataProvider class when generating the
     * corresponding file from the template.
     *
     */
    const DEV_CUSTOM_DATA_DIR = 'dev/mage-bundle/custom-data/';

    /**
     * Locate a directory within a Magento application
     *
     * @return string
     */
    public function locate(): string;
}