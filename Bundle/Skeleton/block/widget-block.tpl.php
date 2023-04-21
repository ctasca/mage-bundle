{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;

class {{class_name}} extends Template implements BlockInterface
{
    /**
    * @return {{class_name}}
    */
    protected function _beforeToHtml(): {{class_name}}
    {
        $this->setTemplate('');
        return parent::_beforeToHtml();
    }
}
