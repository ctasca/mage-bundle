<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

/**
 * Data provider class for template makers
 */
class DataProvider
{
    protected array $data = [];
    protected static array $_underscoreCache = [];

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
        $key = substr($method, 0, 3);
        switch ($key) {
            case 'get':
                return $this->getData($key);
            case 'set':
                $value = $args[0] ?? null;
                $this->data[$key] = $value;
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
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }
        $result = strtolower(trim(preg_replace('/([A-Z]|[0-9]+)/', "_$1", $name), '_'));
        self::$_underscoreCache[$name] = $result;
        return $result;
    }
}