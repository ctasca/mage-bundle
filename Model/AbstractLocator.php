<?php

// phpcs:disable SlevomatCodingStandard.Classes.RequireConstructorPropertyPromotion.RequiredConstructorPropertyPromotion


declare(strict_types=1);

namespace Ctasca\MageBundle\Model;

use Ctasca\MageBundle\Api\LocatorInterface;
use Ctasca\MageBundle\Logger\Logger;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\Read;
use Magento\Framework\Filesystem\Directory\ReadFactory;
use Magento\Framework\Filesystem\Directory\Write;
use Magento\Framework\Filesystem\Directory\WriteFactory;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

abstract class AbstractLocator implements LocatorInterface
{
    protected Filesystem $filesystem;
    protected File $file;
    protected ReadFactory $readFactory;
    protected WriteFactory $writeFactory;
    protected Logger $logger;
    protected JsonSerializer $jsonSerializer;
    protected string $dirname;
    protected string $templateFilename = '';

    /**
     * @return string
     */
    abstract public function getTemplateFilename(): string;

    /**
     * @param string $templateFilename
     * @return \Ctasca\MageBundle\Api\LocatorInterface
     */
    abstract public function setTemplateFilename(string $templateFilename): LocatorInterface;

    /**
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Io\File $file
     * @param \Magento\Framework\Filesystem\Directory\ReadFactory $readFactory
     * @param \Magento\Framework\Filesystem\Directory\WriteFactory $writeFactory
     * @param \Ctasca\MageBundle\Logger\Logger $logger
     * @param \Magento\Framework\Serialize\Serializer\Json $jsonSerializer
     * @param string $dirname
     */
    public function __construct(
        Filesystem $filesystem,
        File $file,
        ReadFactory $readFactory,
        WriteFactory $writeFactory,
        Logger $logger,
        JsonSerializer $jsonSerializer,
        string $dirname
    ) {
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->readFactory = $readFactory;
        $this->writeFactory = $writeFactory;
        $this->logger = $logger;
        $this->jsonSerializer = $jsonSerializer;
        $this->dirname = $dirname;
    }

    /**
     * @param string $path
     * @return \Magento\Framework\Filesystem\Directory\Read
     */
    public function getRead(string $path): Read
    {
        return $this->readFactory->create($path);
    }

    /**
     * @param string $path
     * @return \Magento\Framework\Filesystem\Directory\Write
     */
    public function getWrite(string $path): Write
    {
        return $this->writeFactory->create($path);
    }

    /**
     * @return \Magento\Framework\Filesystem\Io\File
     */
    public function getIoFile(): File
    {
        return $this->file;
    }
}
