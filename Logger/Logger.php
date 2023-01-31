<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Logger;

use Magento\Framework\App\Config\ScopeConfigInterface;

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
     * @param $message
     * @param array $context
     * @return void
     */
    public function logInfo($message, array $context = []): void
    {
        if ($this->isLoggingEnabled()) {
            $this->info($message, $context);
        }
    }

    /**
     * @param $message
     * @param array $context
     * @return void
     */
    public function logError($message, array $context = []): void
    {
        if ($this->isLoggingEnabled()) {
            $this->error($message, $context);
        }
    }

    /**
     * @return bool
     */
    private function isLoggingEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::LOGGING_ENABLED_CONFIG_PATH);
    }
}
