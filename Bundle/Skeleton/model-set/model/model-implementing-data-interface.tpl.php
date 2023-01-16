{{php}}
declare(strict_types=1);

namespace {{model_namespace}};

use Magento\Framework\Model\AbstractModel;
use {{use_resource_model}} as {{class_name}}ResourceModel;
use {{module_namespace}}\Api\Data\{{data_interface}};

class {{class_name}} extends AbstractModel implements {{data_interface}}
{
    protected function _construct(): void
    {
        $this->_init({{class_name}}ResourceModel::class);
    }

    // TODO Implement the Interface
}
