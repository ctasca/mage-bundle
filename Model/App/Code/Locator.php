<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\App\Code;

use Ctasca\MageBundle\Api\LocatorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Ctasca\MageBundle\Logger\Logger;

/**
 * Locate a company/module directory within app/code.
 *
 * Calling locate method will generate the directory if it doesn't exist.
 */
class Locator implements LocatorInterface
{
    /**
     * Code directory name (within app directory)
     */
    const CODE_DIR = 'code/';

    private Filesystem $filesystem;
    private File $file;
    private Logger $logger;
    private string $moduleName;

    /**
     * @param Filesystem $filesystem
     * @param File $file
     * @param Logger $logger
     * @param string $moduleName
     */
    public function __construct(
        Filesystem $filesystem,
        File $file,
        Logger $logger,
        string $moduleName
    ) {
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->logger = $logger;
        $this->moduleName = $moduleName;
    }

    /**
     * {@inheritdoc}
     */
    public function locate(): string
    {
        $directory = $this->filesystem
            ->getDirectoryRead(DirectoryList::APP)
            ->getAbsolutePath(self::CODE_DIR . $this->moduleName);
        try {
            $this->file->checkAndCreateFolder($directory);
            $this->logger->info(__METHOD__ . " Locating directory/caller:", [$directory, debug_backtrace()[1]['function']]);
            return $directory;
        } catch (\Exception $e) {
            $this->logger->error(__METHOD__ . " Exception during creating/locating directory", [$directory]);
            return "";
        }
    }
}