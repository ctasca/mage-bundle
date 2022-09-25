<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template\CustomData;

use Magento\Framework\App\Filesystem\DirectoryList;
use Ctasca\MageBundle\Model\AbstractLocator;

class Locator extends AbstractLocator
{
    /**
     * Directory where custom data for templates should be located.
     * To provide custom data for templates, name the file exactly as the template
     * (with .php extension if it is an XML template)
     * returning an array of setters methods and corresponding
     * values that will be added to the DataProvider class when generating the
     * corresponding file from the template.
     *
     */
    const DEV_CUSTOM_DATA_DIR
        = 'dev' . DIRECTORY_SEPARATOR . 'mage-bundle' . DIRECTORY_SEPARATOR . 'custom-data';

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