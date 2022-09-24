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
        $templateFile = $this->isTemplateFoundInPubMedia();
        if (!is_null($templateFile)) {
            return $templateFile;
        }
        $templateFile = $this->isTemplateFoundInSkeletonDirectory();
        if (!is_null($templateFile)) {
            return $templateFile;
        }
        throw new \Exception(__("Could not locate template file: " . $this->getTemplateFilename()));
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

        $templateFile = $pubMediaTemplateDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename();

        if (file_exists($templateFile)) {
            return $templateFile;
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

        $templateFile = $skeletonDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename();

        if (file_exists($templateFile)) {
            return $templateFile;
        }
        return null;
    }
}