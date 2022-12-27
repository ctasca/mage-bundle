<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

use Magento\Framework\App\Filesystem\DirectoryList;
use Ctasca\MageBundle\Model\AbstractLocator;

class Locator extends AbstractLocator
{
    /**
     * {@inheritdoc}
     */
    public function getTemplateFilename(): string
    {
        return $this->templateFilename;
    }

    /**
     * {@inheritdoc}
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
        $templateFileDirectory = $this->isTemplateFoundInDevDirectory();
        if (!is_null($templateFileDirectory)) {
            $this->logger->info(
                __METHOD__ . " Locating directory -> caller:",
                [$templateFileDirectory, debug_backtrace()[1]['function']]
            );
            return $templateFileDirectory;
        }
        $templateFileDirectory = $this->isTemplateFoundInSkeletonDirectory();
        if (!is_null($templateFileDirectory)) {
            $this->logger->info(
                __METHOD__ . " Locating directory -> caller:",
                [$templateFileDirectory, debug_backtrace()[1]['function']]
            );
            return $templateFileDirectory;
        }
        throw new \Exception("Could not locate template file: " . $this->getTemplateFilename());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getTemplatesChoices(): array
    {
        $templatesDirectory = $this->locate();
        return $this->getRead($templatesDirectory)->read();
    }

    /**
     * @return array
     */
    public function getAreaChoices(): array
    {
        return ['global', 'adminhtml', 'frontend', 'crontab', 'webapi_rest', 'webapi_soap'];
    }

    /**
     * @return array
     */
    public function getApiAreaChoices(): array
    {
        return ['functional', 'data'];
    }

    /**
     * @return array
     */
    public function getRouteChoices(): array
    {
        return ['standard', 'admin'];
    }

    /**
     * @return string
     */
    public function getCustomDataFilename(): string
    {
        if (strpos($this->getTemplateFilename(), '.xml') > 0) {
            return str_replace('xml', 'php', $this->getTemplateFilename());
        }
        return $this->getTemplateFilename();
    }

    /**
     * Returns whether template file is found in dev/mage-bundle/* directory
     * @return string|null
     */
    private function isTemplateFoundInDevDirectory(): ?string
    {
        $devTemplateDir = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath('dev' . DIRECTORY_SEPARATOR . self::DEV_MAGEBUNDLE_DIRNAME . $this->dirname);
        if (!empty($this->getTemplateFilename()) && file_exists($devTemplateDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())) {
            return $devTemplateDir . DIRECTORY_SEPARATOR;
        } elseif (file_exists($devTemplateDir)) {
            return $devTemplateDir . DIRECTORY_SEPARATOR;
        }
        return null;
    }

    /**
     * Returns whether template file is found in vendor/ctasca/mage-bundle/Bundle/Skeleton/* directory
     * @return string|null
     */
    private function isTemplateFoundInSkeletonDirectory(): ?string
    {
        $skeletonDir = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(self::VENDOR_SKELETON_PATH_DIR . $this->dirname);

        if (!empty($this->getTemplateFilename()) && file_exists($skeletonDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())) {
            return $skeletonDir . DIRECTORY_SEPARATOR;
        } elseif (file_exists($skeletonDir)) {
            return $skeletonDir . DIRECTORY_SEPARATOR;
        }
        return null;
    }
}