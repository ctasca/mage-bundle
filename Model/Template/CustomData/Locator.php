<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template\CustomData;

use Magento\Framework\App\Filesystem\DirectoryList;
use Ctasca\MageBundle\Model\AbstractLocator;

class Locator extends AbstractLocator
{
    private string $templateFilename = '';

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
     */
    public function locate(): string
    {
        $customDataDirectory =  $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(self::DEV_CUSTOM_DATA_DIR);
        $this->file->checkAndCreateFolder($customDataDirectory);
        $this->logger->info(__METHOD__ . " Locating directory -> caller:", [$customDataDirectory, debug_backtrace()[1]['function']]);
        return $customDataDirectory;
    }

    /**
     * @return array
     */
    public function getCustomData(): array
    {
        $devMageBundleCustomDataDir = $this->locate();
        if (!empty($this->getTemplateFilename()) &&
            file_exists($devMageBundleCustomDataDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())
        ) {
            return include $devMageBundleCustomDataDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename();
        }
        return [];
    }
}