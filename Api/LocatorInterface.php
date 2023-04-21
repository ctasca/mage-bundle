<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Api;

use Magento\Framework\Filesystem\Directory\Read;
use Magento\Framework\Filesystem\Directory\Write;
use Magento\Framework\Filesystem\Io\File;

interface LocatorInterface
{
    /**
     * Code directory name (within app directory)
     */
    public const CODE_DIR = 'code/';

    /**
     * Directory name to be created in dev directory
     */
    public const DEV_MAGEBUNDLE_DIRNAME = 'mage-bundle/';

    /**
     * Bundle/Skeleton directory relative path
     */
    public const VENDOR_SKELETON_PATH_DIR = 'vendor/ctasca/mage-bundle/Bundle/Skeleton/';

    /**
     * Directory where custom data for templates should be located.
     * To provide custom data for templates, name the file exactly as the template
     * (with .php extension if it is an XML template)
     * returning an array of setters methods and corresponding
     * values that will be added to the DataProvider class when generating the
     * corresponding file from the template.
     *
     */
    public const DEV_CUSTOM_DATA_DIR = 'dev/mage-bundle/custom-data/';

    /**
     * Locate a directory within a Magento application
     *
     * @return string
     */
    public function locate(): string;

    /**
     * @param string $path
     * @return \Magento\Framework\Filesystem\Directory\Read
     */
    public function getRead(string $path): Read;

    /**
     * @param string $path
     * @return \Magento\Framework\Filesystem\Directory\Write
     */
    public function getWrite(string $path): Write;

    /**
     * @return \Magento\Framework\Filesystem\Io\File
     */
    public function getIoFile(): File;
}
