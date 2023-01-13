{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Monolog\Logger;
use Magento\Framework\Logger\Handler\Base;

class {{class_name}} extends Base
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
    protected $fileName = '/var/log/{{log_filename}}}';
}
