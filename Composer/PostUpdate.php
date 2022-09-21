<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Composer;

use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Composer\Util\Filesystem as ComposerFilesystem;
use Ctasca\MageBundle\Framework\Filesystem as MagentoFilesystem;
use Ctasca\MageBundle\Framework\File;
use Ctasca\MageBundle\Framework\DirectoryList;

class PostUpdate
{
    /**
     * Post update scripts execute after install and update
     *
     * @param Event $event
     * @return void
     */
    public static function copyTemplates(Event $event): void
    {
        $magentoFile = new File();
        $skeletonDir = $event->getComposer()->getConfig()->get('ctasca/mage-bundle/Bundle/Skeleton');
        var_dump($skeletonDir);
        $magentoFilesystem = new MagentoFilesystem();
        $composerFilesystem = new ComposerFilesystem();
        $mediaDirectory = $magentoFilesystem->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath("mage-bundle");
        $magentoFile->checkAndCreateFolder($mediaDirectory);
        $composerFilesystem->copy($skeletonDir, $mediaDirectory);
    }
}