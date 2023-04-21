{{php}}

declare(strict_types=1);

namespace {{namespace}};

use Magento\Framework\Api\SearchResults;
use {{api_namespace}}\Data\{{repository_name}}SearchResultInterface;

class {{repository_name}}SearchResult extends SearchResults implements {{repository_name}}SearchResultInterface
{
}
