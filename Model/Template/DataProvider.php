<?php
declare(strict_types=1);

namespace Ctasca\MageBundle\Model\Template;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

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
    public function __call(string $method, array $args): ?string
    {
        switch (substr($method, 0, 3)) {
            case 'get':
                $key = $this->_underscore(substr($method, 3));
                return $this->getData($key);
            case 'set':
                $key = $this->_underscore(substr($method, 3));
                $value = $args[0] ?? null;
                $this->__set($key, $value);
        }
        throw new LocalizedException(
            new Phrase('Invalid method %1::%2', [get_class($this), $method])
        );
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