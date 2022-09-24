<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Composer;

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

class PostUpdate
{
    /**
     * Post update scripts
     * @param Event $event
     * @return void
     */
    public static function copyTemplates(Event $event): void
    {
        $magentoFile = new File();
        $rootDirectory = realpath("../../../");
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $readFactory = new ReadFactory(new DriverPool());
        $writeFactory = new WriteFactory(new DriverPool());
        $magentoFilesystem = new MagentoFilesystem(
            new DirectoryList($rootDirectory),
            $readFactory,
            $writeFactory
        );
        $composerFilesystem = new ComposerFilesystem();
        $mediaDirectory = $magentoFilesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath("mage-bundle");
        $magentoFile->checkAndCreateFolder($mediaDirectory);
        var_dump($vendorDir);
        //$composerFilesystem->copy($skeletonDir, $mediaDirectory);
    }
}