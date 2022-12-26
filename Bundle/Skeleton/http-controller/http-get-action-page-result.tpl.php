{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Result\Page;

class {{class_name}} implements HttpGetActionInterface
{
    /**
     * @param RequestInterface $request
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly PageFactory $resultPageFactory
    ){}

    /**
     * @return Page
     */
    public function execute(): Page
    {
        return $this->resultPageFactory->create();
    }
}
