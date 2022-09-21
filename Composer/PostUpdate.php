<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Composer;

include_once __DIR__ . "/../../../magento/framework/Filesystem.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/Io/IoInterface.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/Io/AbstractIo.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/Io/File.php";
include_once __DIR__ . "/../../../magento/framework/App/Filesystem/DirectoryList.php";

use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Util\Filesystem as ComposerFilesystem;
use Magento\Framework\Filesystem as MagentoFilesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\App\Filesystem\DirectoryList;

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
        $skeletonDir = $event->getComposer()->getConfig()->get('mage-bundle/Bundle/Skeleton');
        $magentoFilesystem = new MagentoFilesystem();
        $composerFilesystem = new ComposerFilesystem();
        $mediaDirectory = $magentoFilesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath("mage-bundle");
        $magentoFile->checkAndCreateFolder($mediaDirectory);
        $composerFilesystem->copy($skeletonDir, $mediaDirectory);
    }
}