<?php
// phpcs:ignoreFile

declare(strict_types=1);

namespace Ctasca\MageBundle\Logger;

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

class Handler extends Base
{
    /**
     * Logging level
     * @var int
     */
    protected $loggerType = Logger::INFO;

    /**
     * File name
     * @var string
     */
    protected $fileName = '/var/log/mage-bundle.log';
}
