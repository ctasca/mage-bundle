{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class {{class_name}} extends Action implements HttpPostActionInterface
{
    /**
    * Authorization level of a basic admin session
    *
    * @see _isAllowed()
    */
    const ADMIN_RESOURCE = '{{module}}::';

    /**
     * @param Context $context
     */
    public function __construct(
        private readonly Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {

    }
}