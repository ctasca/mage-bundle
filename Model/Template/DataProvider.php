<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

use Ctasca\MageBundle\Logger\Logger;

/**
 * Data provider class for template makers
 */
class DataProvider
{
    private Logger $logger;
    protected array $data = [];

    /**
     * @param Logger $logger
     */
    public function __construct(Logger $logger) {
        $this->logger = $logger;
    }

    /**
     * Set/Get attribute wrapper
     *
     * @param   string $method
     * @param   array $args
     * @return  string|null
     * @throws LocalizedException
     */
    public function __call(string $method, array $args)
    {
        $methodType = strtolower(substr($method, 0, 3));
        $dataKey = $this->_underscore(strtolower(substr($method, 3)));
        $this->logger->info(__METHOD__ . " __call method:", [$method]);
        $this->logger->info(__METHOD__ . " __call extracted key:", [$dataKey]);
        switch ($methodType) {
            case 'get':
                $this->logger->info(__METHOD__ . " get data:", $this->data);
                return $this->getData($dataKey);
            case 'set':
                $value = $args[0] ?? null;
                $this->data[$dataKey] = $value;
                $this->logger->info(__METHOD__ . " set data:", $this->data);
                break;
            default:
                return null;
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @return void
     */
    public function __set(string $key, string $value)
    {
        $method = 'set' . ucfirst($key);
        if (is_callable([$this, $method])) {
            $this->{$method}($value);
        } else {
            $this->data[$key] = $value;
        }
    }

    /**
     * @param string $key
     * @return string
     */
    public function __get(string $key): string
    {
        return $this->data[$key];
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getData(string $key): ?string
    {
        return $this->data[$key] ?? null;
    }

    /**
     * Converts field names for setters and getters
     *
     * $this->setMyField($value) === $this->setData('my_field', $value)
     * Uses cache to eliminate unnecessary preg_replace
     *
     * @param string $name
     * @return string
     */
    protected function _underscore(string $name): string
    {
        return strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
    }
}