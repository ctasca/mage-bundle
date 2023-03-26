{{php}}
declare(strict_types=1);

namespace {{namespace}};

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class {{class_name}} extends Generic implements ButtonProviderInterface
{
    /**
     * @inerhitDoc
     */
    public function getButtonData(): array
    {
        return [
            'label' => __(''),
            'class' => '',
            'on_click' => "return false",
            'sort_order' => 100
        ];
    }
}
