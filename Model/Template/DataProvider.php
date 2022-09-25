<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

/**
 * Data provider class for template makers
 */
class DataProvider
{
    protected array $data = [];

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
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}