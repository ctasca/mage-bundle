{{php}}

declare(strict_types=1);

namespace {{model_namespace}};

use Magento\Framework\Model\AbstractModel;
use {{use_resource_model}} as {{class_name}}ResourceModel;

class {{class_name}} extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init({{class_name}}ResourceModel::class);
    }
}
