{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\Event\ObserverInterface;

class {{class_name}} implements ObserverInterface
{
    /**
     * @inheritdoc
     */
    public function execute(\Magento\Framework\Event\Observer $observer): ObserverInterface
    {
        return $this;
    }
}
