<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Logger;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Monolog\DateTimeImmutable;

class Logger extends \Monolog\Logger
{
    /**
     * Path to system config
     */
    const LOGGING_ENABLED_CONFIG_PATH = 'ctasca_magebundle/settings/logging_enabled';

    private ScopeConfigInterface $scopeConfig;

    /**
     * @param string $name
     * @param ScopeConfigInterface $scopeConfig
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(
        string $name,
        ScopeConfigInterface $scopeConfig,
        array $handlers = [],
        array $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param int $level
     * @param string $message
     * @param array $context
     * @param DateTimeImmutable|null $datetime
     * @return bool
     */
    public function addRecord(
        int $level,
        string $message,
        array $context = [],
        DateTimeImmutable $datetime = null
    ): bool {
        if ($this->isLoggingEnabled()) {
            return parent::addRecord($level, $message, $context, $datetime);
        }
        return false;
    }

    /**
     * @return bool
     */
    private function isLoggingEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::LOGGING_ENABLED_CONFIG_PATH);
    }
}
