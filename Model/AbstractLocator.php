<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model;

use Ctasca\MageBundle\Api\LocatorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem\Directory\Read;
use Magento\Framework\Filesystem\Directory\Write;
use Ctasca\MageBundle\Logger\Logger;

abstract class AbstractLocator implements LocatorInterface
{
    protected Filesystem $filesystem;
    protected File $file;
    protected ReadFactory $readFactory;
    protected WriteFactory $writeFactory;
    protected Logger $logger;
    protected string $dirname;

    /**
     * @param Filesystem $filesystem
     * @param File $file
     * @param ReadFactory $readFactory
     * @param WriteFactory $writeFactory
     * @param Logger $logger
     * @param string $dirname
     */
    public function __construct(
        Filesystem $filesystem,
        File $file,
        ReadFactory $readFactory,
        WriteFactory $writeFactory,
        Logger $logger,
        string $dirname
    ) {
        $this->filesystem = $filesystem;
        $this->readFactory = $readFactory;
        $this->writeFactory = $writeFactory;
        $this->logger = $logger;
        $this->dirname = $dirname;
    }

    /**
     * @param string $path
     * @return Read
     */
    public function getRead(string $path): Read
    {
        return $this->readFactory->create($path);
    }

    /**
     * @param string $path
     * @return Write
     */
    public function getWrite(string $path): Write
    {
        return $this->writeFactory->create($path);
    }
}