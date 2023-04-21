<?php

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification


declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

use Ctasca\MageBundle\Exception\CouldNotLocateTemplateException;
use Ctasca\MageBundle\Model\AbstractLocator;
use Magento\Framework\App\Filesystem\DirectoryList;

class Locator extends AbstractLocator
{
    /**
     * @return string
     */
    public function getTemplateFilename(): string
    {
        return $this->templateFilename;
    }

    /**
     * @param string $templateFilename
     * @return $this
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

        if ($templateFileDirectory !== null) {
            $this->logger->logInfo(
                __METHOD__ . " Locating directory -> caller:",
                [$templateFileDirectory, debug_backtrace()[1]['function']]
            );

            return $templateFileDirectory;
        }

        $templateFileDirectory = $this->isTemplateFoundInSkeletonDirectory();

        if ($templateFileDirectory !== null) {
            $this->logger->logInfo(
                __METHOD__ . " Locating directory -> caller:",
                [$templateFileDirectory, debug_backtrace()[1]['function']]
            );

            return $templateFileDirectory;
        }

        throw new CouldNotLocateTemplateException(
            "Could not locate template file: " . $this->getTemplateFilename()
        );
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
     * @return array
     */
    public function getWebAreaChoices(): array
    {
        return ['base', 'adminhtml', 'frontend'];
    }

    /**
     * Returns whether template file is found in dev/mage-bundle/* directory
     *
     * @return string|null
     */
    private function isTemplateFoundInDevDirectory(): ?string
    {
        $devTemplateDir = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath('dev' . DIRECTORY_SEPARATOR . self::DEV_MAGEBUNDLE_DIRNAME . $this->dirname);

        if (
            !empty($this->getTemplateFilename()) &&
            $this->file->fileExists($devTemplateDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())
        ) {
            return $devTemplateDir . DIRECTORY_SEPARATOR;
        }

        if ($this->file->fileExists($devTemplateDir, false)) {
            return $devTemplateDir . DIRECTORY_SEPARATOR;
        }

        return null;
    }

    /**
     * Returns whether template file is found in vendor/ctasca/mage-bundle/Bundle/Skeleton/* directory
     *
     * @return string|null
     */
    private function isTemplateFoundInSkeletonDirectory(): ?string
    {
        $skeletonDir = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(self::VENDOR_SKELETON_PATH_DIR . $this->dirname);

        if (
            !empty($this->getTemplateFilename()) &&
            $this->file->fileExists($skeletonDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())
        ) {
            return $skeletonDir . DIRECTORY_SEPARATOR;
        }

        if ($this->file->fileExists($skeletonDir, false)) {
            return $skeletonDir . DIRECTORY_SEPARATOR;
        }

        return null;
    }
}
