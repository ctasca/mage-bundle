<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Composer;

/**
 * Require composer autoload file
 */
require __DIR__ . '/../../../autoload.php';

use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Util\Filesystem as ComposerFilesystem;
use Magento\Framework\Filesystem as MagentoFilesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem\DriverPool;
use Ctasca\MageBundle\Api\LocatorInterface;
/**
 * Class used in composer post install command script
 */
class PostInstall
{
    /**
     * Argument to be passed to realpath function
     *
     * @todo Make this configurable somehow
     */
    const MAGENTO_ROOT_REALPATH_ARGUMENT = '../../../';

    /**
     * Post install script
     *
     * Copy files from Bundle/Skeleton directory to Magento pub/media directory
     * Allows defining developer own templates
     *
     * @param Event $event
     * @return void
     */
    public static function copyTemplates(Event $event): void
    {
        $magentoFile = new File();
        $rootMagentoDirectory = realpath(self::MAGENTO_ROOT_REALPATH_ARGUMENT);
        $readFactory = new ReadFactory(new DriverPool());
        $writeFactory = new WriteFactory(new DriverPool());
        $magentoFilesystem = new MagentoFilesystem(
            new DirectoryList($rootMagentoDirectory),
            $readFactory,
            $writeFactory
        );
        $composerFilesystem = new ComposerFilesystem();
        $mediaDirectory = $magentoFilesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath(LocatorInterface::PUB_MEDIA_MAGE_BUNDLE_DIRNAME);
        $magentoFile->checkAndCreateFolder($mediaDirectory);
        $skeletonDir = $magentoFilesystem->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(LocatorInterface::VENDOR_SKELETON_PATH_DIR);
        $composerFilesystem->copy($skeletonDir, $mediaDirectory);
    }
}