<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model;

use Ctasca\MageBundle\Api\LocatorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Io\File;
use Ctasca\MageBundle\Logger\Logger;

abstract class AbstractLocator implements LocatorInterface
{
    protected Filesystem $filesystem;
    protected File $file;
    protected Logger $logger;
    protected string $dirname;

    /**
     * @param Filesystem $filesystem
     * @param File $file
     * @param Logger $logger
     * @param string $dirname
     */
    public function __construct(
        Filesystem $filesystem,
        File $file,
        Logger $logger,
        string $dirname
    ) {
        $this->filesystem = $filesystem;
        $this->file = $file;
        $this->logger = $logger;
        $this->dirname = $dirname;
    }
}