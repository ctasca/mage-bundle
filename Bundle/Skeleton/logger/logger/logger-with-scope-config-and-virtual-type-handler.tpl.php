{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Config\ScopeConfigInterface;
use Monolog\DateTimeImmutable;
use Monolog\Handler\HandlerInterface;

class {{class_name}} extends \Monolog\Logger
{
    public const LOGGER_ENABLED_CONFIG_PATH = '';

    /**
     * @param string $name
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param HandlerInterface[] $handlers
     * @param callable[] $processors
     */
    public function __construct(
        string $name,
        private readonly ScopeConfigInterface $scopeConfig,
        array $handlers = [],
        array $processors = []
    ) {
        parent::__construct($name, $handlers, $processors);
    }

    public function addRecord(
        int $level,
        string $message,
        array $context = [],
        ?DateTimeImmutable $datetime = null
    ): bool {
        if ($this->isLoggingEnabled() === true) {
            return parent::addRecord($level, $message, $context, $datetime);
        }

        return false;
    }

    /**
    * @return bool
    */
    private function isLoggingEnabled(): bool
    {
        return (bool) $this->scopeConfig->getValue(self::LOGGER_ENABLED_CONFIG_PATH);
    }
}
