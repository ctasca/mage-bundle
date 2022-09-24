<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Composer;

include_once __DIR__ . "/../../../../app/bootstrap.php";
include_once __DIR__ . "/../../../magento/framework/ObjectManagerInterface.php";
include_once __DIR__ . "/../../../magento/framework/ObjectManagerInterface.php";
include_once __DIR__ . "/../../../magento/framework/ObjectManager/ConfigInterface.php";
include_once __DIR__ . "/../../../magento/framework/ObjectManager/FactoryInterface.php";
include_once __DIR__ . "/../../../magento/framework/ObjectManager/ObjectManager.php";
include_once __DIR__ . "/../../../magento/framework/App/ObjectManager.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/Io/IoInterface.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/Io/AbstractIo.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/Io/File.php";
include_once __DIR__ . "/../../../magento/framework/Filesystem/DirectoryList.php";
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
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $magentoFile = $objectManager->create(File::class);
        $magentoFilesystem = $objectManager->create(MagentoFilesystem::class);
        $composerFilesystem = new ComposerFilesystem();
        $mediaDirectory = $magentoFilesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath("mage-bundle");
        $magentoFile->checkAndCreateFolder($mediaDirectory);
        //$composerFilesystem->copy($skeletonDir, $mediaDirectory);
    }
}