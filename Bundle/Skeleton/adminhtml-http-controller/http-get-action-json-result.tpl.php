{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Json;

class {{class_name}} extends Action implements HttpGetActionInterface
{
    /**
    * Authorization level of a basic admin session
    *
    * @see _isAllowed()
    */
    const ADMIN_RESOURCE = '{{module}}::';

    /**
     * @param Context $context
     * @param RequestInterface $request
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        private readonly Context $context,
        private readonly RequestInterface $request,
        private readonly JsonFactory $jsonFactory
    ){
        parent::__construct($context);
    }

    /**
     * @return Json
     */
    public function execute(): Json
    {
        $jsonResponse = $this->jsonFactory->create();
        return $jsonResponse->setData([]);
    }
}
