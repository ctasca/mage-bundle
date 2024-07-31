<?php

declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Pwd;

use Ctasca\MageBundle\Api\LocatorInterface;
use Ctasca\MageBundle\Model\AbstractLocator;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Locate a company/module directory within app/code.
 *
 * Calling locate method will generate the directory if it doesn't exist.
 */
class Locator extends AbstractLocator
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function locate(): string
    {
        try {
            $pwdJson = $this->getRead('dev/mage-bundle')
                ->readFile('pwd.json');
        } catch (\Exception $e) {
            return '';
        }
        $pwd = $this->jsonSerializer->unserialize($pwdJson);
        $directory = $this->filesystem
            ->getDirectoryRead(DirectoryList::ROOT)
            ->getAbsolutePath($pwd['pwd'] . $this->dirname);
        $this->file->checkAndCreateFolder($directory);
        try {
            $this->logger->logInfo(
                __METHOD__ . " Locating directory -> caller:",
                [$directory, debug_backtrace()[1]['function']]
            );

            return $directory;
        } catch (\Exception $e) {
            $this->logger->logError(__METHOD__ . " Exception during creating/locating directory", [$directory]);
            $this->logger->logError(__METHOD__ . " Exception message", [$e->getMessage()]);
            throw $e;
        }
    }

    /**
     * @return string
     */
    public function getTemplateFilename(): string
    {
        return '';
    }

    /**
     * @param string $templateFilename
     * @return \Ctasca\MageBundle\Api\LocatorInterface
     */
    public function setTemplateFilename(string $templateFilename): LocatorInterface //@phpcs:ignore
    {
        return $this;
    }
}
