{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Customer\CustomerData\SectionSourceInterface;

class {{class_name}} implements SectionSourceInterface
{
    /**
     * @inheritdoc
     */
    public function getSectionData()
    {
        return [];
    }
}
