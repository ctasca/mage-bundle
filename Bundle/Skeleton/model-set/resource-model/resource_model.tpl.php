{{php}}
declare(strict_types=1);

namespace {{resource_model_namespace}};

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class {{class_name}} extends AbstractDb
{
    /** @var string Main table name */
    const MAIN_TABLE = '{{main_table}}';

    /** @var string Main table primary key field name */
    const ID_FIELD_NAME = '{{id_field_name}}';

    protected function _construct(): void
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}