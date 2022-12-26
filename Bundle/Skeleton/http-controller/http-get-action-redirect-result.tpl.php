{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\Result\Redirect;

class {{class_name}} implements HttpGetActionInterface
{
    /**
     * @param RequestInterface $request
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly RedirectFactory $resultRedirectFactory
    ){}

    /**
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('');
        return $resultRedirect;
    }
}
