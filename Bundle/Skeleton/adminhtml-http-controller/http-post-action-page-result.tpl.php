{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;

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
     * @param RequestInterface $request
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        private readonly Context $context,
        private readonly RequestInterface $request,
        private readonly PageFactory $resultPageFactory
    ){
        parent::__construct($context);
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        return $this->resultPageFactory->create();
    }
}
