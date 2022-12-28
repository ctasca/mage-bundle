<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template\CustomData;

use Magento\Framework\App\Filesystem\DirectoryList;
use Ctasca\MageBundle\Model\AbstractLocator;

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
     * @return Locator
     */
    public function setTemplateFilename(string $templateFilename): Locator
    {
        if (strpos($templateFilename, '.xml') !== false) {
            $templateFilename = str_replace('.xml', '.php', $templateFilename);
        }
        $this->templateFilename = $templateFilename;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function locate(): string
    {
        $customDataDirectory =  $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(self::DEV_CUSTOM_DATA_DIR);
        $this->file->checkAndCreateFolder($customDataDirectory);
        $this->logger->info(
            __METHOD__ . " Locating directory -> caller:",
            [
                $customDataDirectory,
                debug_backtrace()[1]['function']
            ]
        );
        return $customDataDirectory;
    }

    /**
     * @return array
     */
    public function getCustomData(): array
    {
        $devMageBundleCustomDataDir = $this->locate();
        $this->logger->info(
            __METHOD__ . " Locating file",
            [
                $this->getTemplateFilename(),
                debug_backtrace()[1]['function']
            ]
        );
        if (!empty($this->getTemplateFilename()) &&
            $this->file->fileExists($devMageBundleCustomDataDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())
        ) {
            $this->logger->info(
                __METHOD__ . " Found custom data file",
                [
                    $devMageBundleCustomDataDir . $this->getTemplateFilename(),
                    debug_backtrace()[1]['function']
                ]
            );
            return include_once ($devMageBundleCustomDataDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename());
        }
        $this->logger->info(
            __METHOD__ . " Did not get custom data file",
            [
                $devMageBundleCustomDataDir . $this->getTemplateFilename(),
                debug_backtrace()[1]['function']
            ]
        );
        return [];
    }
}
