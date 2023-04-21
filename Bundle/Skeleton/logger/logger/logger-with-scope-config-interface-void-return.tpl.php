{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Config\ScopeConfigInterface;

class {{class_name}} extends \Monolog\Logger
{
    /**
     * Path to system config
     */
    public const LOGGING_ENABLED_CONFIG_PATH = '';

    /**
     * @param string $name
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param array $handlers
     * @param array $processors
     */
    public function __construct(
        string $name,
        private readonly ScopeConfigInterface $scopeConfig,
        array $handlers = [],
        array $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
    }

    /**
     * {@inheritdoc}
     */
    public function info($message, array $context = []): void
    {
        if ($this->isLoggingEnabled()) {
            return parent::info($message, $context);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = []): void
    {
        if ($this->isLoggingEnabled()) {
            parent::error($message, $context);
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
