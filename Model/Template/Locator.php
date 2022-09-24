<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

use Magento\Framework\App\Filesystem\DirectoryList;
use Ctasca\MageBundle\Model\AbstractLocator;

class Locator extends AbstractLocator
{
    private string $templateFilename;

    /**
     * @return string
     */
    public function getTemplateFilename(): string
    {
        return $this->templateFilename;
    }

    /**
     * @param string $templateFilename
     * @return Locator
     */
    public function setTemplateFilename(string $templateFilename): Locator
    {
        $this->templateFilename = $templateFilename;
        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function locate(): string
    {
        $templateFileDirectory = $this->isTemplateFoundInPubMedia();
        if (!is_null($templateFileDirectory)) {
            $this->logger->info(__METHOD__ . " Locating directory -> caller:", [$templateFileDirectory, debug_backtrace()[1]['function']]);
            return $templateFileDirectory;
        }
        $templateFileDirectory = $this->isTemplateFoundInSkeletonDirectory();
        if (!is_null($templateFileDirectory)) {
            $this->logger->info(__METHOD__ . " Locating directory -> caller:", [$templateFileDirectory, debug_backtrace()[1]['function']]);
            return $templateFileDirectory;
        }
        throw new \Exception("Could not locate template file: " . $this->getTemplateFilename());
    }

    /**
     * Returns whether template file is found in pub/media/mage-bundle/* directory
     * @return string|null
     */
    private function isTemplateFoundInPubMedia(): ?string
    {
        $pubMediaTemplateDir = $this->filesystem
            ->getDirectoryRead(DirectoryList::MEDIA)
            ->getAbsolutePath(self::PUB_MEDIA_MAGE_BUNDLE_DIRNAME . $this->dirname);

        if (file_exists($pubMediaTemplateDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())) {
            return $pubMediaTemplateDir . DIRECTORY_SEPARATOR;
        }
        return null;
    }

    /**
     * Returns whether template file is found in pub/media/mage-bundle/* directory
     * @return string|null
     */
    private function isTemplateFoundInSkeletonDirectory(): ?string
    {
        $skeletonDir = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(self::VENDOR_SKELETON_PATH_DIR . $this->dirname);

        if (file_exists($skeletonDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())) {
            return $skeletonDir . DIRECTORY_SEPARATOR;
        }
        return null;
    }
}