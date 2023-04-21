{{php}}

declare(strict_types=1);

namespace {{collection_namespace}};

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use {{use_model}};
use {{use_resource_model}} as {{class_name}}ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init({{class_name}}::class, {{class_name}}ResourceModel::class);
    }
}
