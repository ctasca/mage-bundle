{{php}}
declare(strict_types=1);

namespace {{model_namespace}};

use Magento\Framework\Model\AbstractModel;

class {{class_name}} extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(\{{resource_model_namespace}}::class);
    }
}