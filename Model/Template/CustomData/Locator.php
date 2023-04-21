<?php

// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
// phpcs:disable SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingTraversableTypeHintSpecification


declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template\CustomData;

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
     * @return \Ctasca\MageBundle\Model\Template\CustomData\Locator
     */
    public function setTemplateFilename(string $templateFilename): Locator
    {
        $this->templateFilename = str_replace(
            ['.xml', '.php', '.jst'],
            array_fill(0, 3, '.json'),
            $templateFilename
        );

        return $this;
    }

    /**
     * @return string
     */
    public function locate(): string
    {
        $customDataDirectory = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath(self::DEV_CUSTOM_DATA_DIR);
        $this->file->checkAndCreateFolder($customDataDirectory);
        $this->logger->logInfo(
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
        $this->logger->logInfo(
            __METHOD__ . " Locating file",
            [
                $this->getTemplateFilename(),
                debug_backtrace()[1]['function']
            ]
        );

        if (
            !empty($this->getTemplateFilename()) &&
            $this->file->fileExists($devMageBundleCustomDataDir . DIRECTORY_SEPARATOR . $this->getTemplateFilename())
        ) {
            $this->logger->logInfo(
                __METHOD__ . " Found custom data file",
                [
                    $devMageBundleCustomDataDir . $this->getTemplateFilename(),
                    debug_backtrace()[1]['function']
                ]
            );
            $fileContent = $this->getRead($devMageBundleCustomDataDir)
                ->readFile($this->getTemplateFilename());

            $unserializedData = $this->jsonSerializer->unserialize($fileContent);

            $this->logger->logInfo(
                __METHOD__ . " Unserialized Data",
                [
                    $unserializedData,
                    debug_backtrace()[1]['function']
                ]
            );

            return $this->jsonSerializer->unserialize($fileContent);
        }

        $this->logger->logInfo(
            __METHOD__ . " Did not get custom data file",
            [
                $devMageBundleCustomDataDir . $this->getTemplateFilename(),
                debug_backtrace()[1]['function']
            ]
        );

        return [];
    }
}
