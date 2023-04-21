<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Ctasca\MageBundle\Composer;

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
     * Copy files from Bundle/Skeleton directory to Magento root dev/ directory
     * Allows defining developer own templates
     *
     * @param Event $event
     * @return void
     */
    public static function copyTemplates(Event $event): void
    {
        /**
         * Require composer autoload file
         */
        require_once __DIR__ . '/../../../autoload.php';

        $magentoFile = new File();
        $driver = (new DriverPool())->getDriver(DriverPool::FILE);
        $rootMagentoDirectory = $driver->getRealPath(self::MAGENTO_ROOT_REALPATH_ARGUMENT);
        $readFactory = new ReadFactory(new DriverPool());
        $writeFactory = new WriteFactory(new DriverPool());
        $magentoFilesystem = new MagentoFilesystem(
            new DirectoryList($rootMagentoDirectory),
            $readFactory,
            $writeFactory
        );
        $composerFilesystem = new ComposerFilesystem();
        $devDirectory = $magentoFilesystem->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath('dev' . DIRECTORY_SEPARATOR . LocatorInterface::DEV_MAGEBUNDLE_DIRNAME);
        $magentoFile->checkAndCreateFolder($devDirectory);
        $skeletonDir = $magentoFilesystem->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(LocatorInterface::VENDOR_SKELETON_PATH_DIR);
        $composerFilesystem->copy($skeletonDir, $devDirectory);
    }
}
