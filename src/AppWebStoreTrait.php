<?php
/**
 * @package evas-php/evas-web-storage
 */
namespace Evas\Web\Storage;

use Evas\Web\App as WebApp;
use Evas\Web\Storage\Cookie;
use Evas\Web\Storage\Session;

/**
 * Константы для свойств.
 */
if (!defined('EVAS_COOKIE_CLASS')) define('EVAS_COOKIE_CLASS', Cookie::class);
if (!defined('EVAS_SESSION_CLASS')) define('EVAS_SESSION_CLASS', Session::class);


/**
 * Расширение класса приложения поддержкой cookie и session.
 * @author Egor Vasyakin <e.vasyakin@itevas.ru>
 * @since 1.0
 */
trait AppWebStoreTrait
{
    /**
     * @var string имя класса cookie
     */
    protected $cookieClass = EVAS_COOKIE_CLASS;

    /**
     * @var string имя класса session
     */
    protected $sessionClass = EVAS_SESSION_CLASS;

    /**
     * @var Cookie объект cookie
     */
    protected $cookie;

    /**
     * @var Session объект session
     */
    protected $session;

    /**
     * Установка имени класса cookie.
     * @param string
     * @return self
     */
    public static function setCookieClass(string $cookieClass)
    {
        return static::instanceSet('cookieClass', $cookieClass);
    }

    /**
     * Установка имени класса session.
     * @param string
     * @return self
     */
    public static function setSessionClass(string $sessionClass)
    {
        return static::instanceSet('sessionClass', $sessionClass);
    }

	/**
     * Получение объекта cookie.
     * @return Cookie
     */
    public static function cookie()
    {
        if (!static::instanceHas('cookie')) {
            $cookieClass = static::instanceGet('cookieClass');
            $cookie = new $cookieClass;
            if (get_called_class() == WebApp::class) {
                $cookie->setHost(static::getHost());
            }
            static::instanceSet('cookie', $cookie);
        }
        return static::instanceGet('cookie');
    }

    /**
     * Получение объекта session.
     * @return Session
     */
    public static function session()
    {
        if (!static::instanceHas('session')) {
            $sessionClass = static::instanceGet('sessionClass');
            $session = new $sessionClass;
            static::instanceSet('session', $session);
        }
        return static::instanceGet('session');
    }
}
