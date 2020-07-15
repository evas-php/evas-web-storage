<?php
/**
 * @package evas-php\evas-web-storage
 */
namespace Evas\Web\Storage;

use Evas\Base\Helpers\PhpHelper;

/**
 * Абстрактный класс хранилища.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
abstract class AbstractStorage
{
    /**
     * @var array параметры
     */
    protected $_params = [];

    /**
     * Установка параметра.
     * @param string имя
     * @param mixed значение
     */
    public function __set(string $name, $value)
    {
        $this->_params[$name] = $value;
    }

    /**
     * Получение параметра.
     * @param string имя
     * @return mixed|null значение
     */
    public function __get(string $name)
    {
        return $this->_params[$name] ?? null;
    }

    /**
     * Проверка наличия параметра.
     * @param string имя
     * @return bool
     */
    public function __isset(string $name)
    {
        return isset($this->_params[$name]) ? true : false;
    }

    /**
     * Удаление параметра.
     * @param string имя
     */
    public function __unset(string $name)
    {
        unset($this->_params[$name]);
    }

    /**
     * Удаление параметра или параметров.
     * @param string|array имя параметра или имена параметров
     * @return self
     */
    public function unset($name)
    {
        assert(is_string($name) || is_array($name));
        if (is_string($name)) {
            // очистка параметра
            $this->__unset($name);
        } else {
            // итеративная очистка параметров
            foreach ($name as &$subname) {
                $this->__unset($subname);
            }
        }
        return $this;
    }

    /**
     * Очистка параметров.
     * @return self
     */
    public function clean()
    {
        $this->_params = [];
        return $this;
    }

    /**
     * Получение параметра или параметров.
     * @param string|array имя параметра или имена параметров
     * @param mixed|null альтернативное значение, если параметра не будет
     * @return string|array|null значение параметра или параметры
     */
    public function get($name = null, $alternativeValue = null)
    {
        assert(is_string($name) || is_array($name) || null === $name);
        if (is_string($name)) {
            // получение параметра
            return $this->__isset($name) ? $this->__get($name) : $alternativeValue;
        } else if (is_array($name)) {
            // итеративное получение параметров
            $params = [];
            if (PhpHelper::isAssoc($name)) foreach ($name as $subname => $alternativeValue) {
                $params[$subname] = $this->get($subname, $alternativeValue);
            } else foreach ($name as &$subname) {
                $params[$subname] = $this->get($subname);
            }
            return $params;
        } else {
            // получение всех параметров
            return $this->_params;
        }
    }

    /**
     * Проверка наличия параметра или параметров.
     * @param string|array имя параметра или имена параметров
     * @param mixed возвращаемое значение в случае наличия
     * @param mixed возвращаемое значение в случае отсутствия
     * @return bool
     */
    public function has($name, $trueValue = true, $falseValue = false)
    {
        assert(is_string($name) || is_array($name));
        if (is_string($name)) {
            // проверка наличия параметра
            return $this->__isset($name) ? $trueValue : $falseValue;
        } else {
            // итеративная проверка наличия параметров
            foreach ($name as &$subname) {
                if (false === $this->__isset($subname)) {
                    return $falseValue;
                }
            }
            return $trueValue;
        }
    }
}
