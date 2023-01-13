{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Config\ScopeConfigInterface;

class {{class_name}} extends \Monolog\Logger
{
    /**
     * Path to system config
     */
    const LOGGING_ENABLED_CONFIG_PATH = '';

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
     * {@inheritdoc}
     */
    public function info($message, array $context = []): bool
    {
        if ($this->isLoggingEnabled()) {
            return parent::info($message, $context);
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function error($message, array $context = []): bool
    {
        if ($this->isLoggingEnabled()) {
            return parent::error($message, $context);
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
