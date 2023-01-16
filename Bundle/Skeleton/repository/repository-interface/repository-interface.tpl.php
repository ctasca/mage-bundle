{{php}}
declare(strict_types=1);

namespace {{api_namespace}};

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use {{api_namespace}}\Data\{{repository_name}}Interface;
use {{api_namespace}}\Data\{{repository_name}}SearchResultInterface;

interface {{repository_name}}RepositoryInterface
{
    /**
     * @param $id
     * @return {{repository_name}}Interface
     * @throws NoSuchEntityException
     */
    public function getById($id): {{repository_name}}Interface;

    /**
     * @param {{repository_name}}Interface ${{repository_name_argument}}
     * @return {{repository_name}}Interface
     */
    public function save({{repository_name}}Interface ${{repository_name_argument}}): {{repository_name}}Interface;

    /**
     * @param {{repository_name}}Interface ${{repository_name_argument}}
     * @return bool
     */
    public function delete({{repository_name}}Interface ${{repository_name_argument}}): bool;

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return {{repository_name}}SearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): {{repository_name}}SearchResultInterface;
}
