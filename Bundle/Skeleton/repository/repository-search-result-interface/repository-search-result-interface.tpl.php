{{php}}
declare(strict_types=1);

namespace {{api_namespace}}\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface {{repository_name}}SearchResultInterface extends SearchResultsInterface
{
    /**
     * @return {{repository_name}}Interface[]
     */
    public function getItems();

    /**
     * @param {{repository_name}}Interface[] $items
     * @return $this
     */
    public function setItems(array $items);

    /**
     * @param int $totalCount
     * @return $this
     */
    public function setTotalCount($totalCount);
}
