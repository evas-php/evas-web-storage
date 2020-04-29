<?php
/**
 * @package evas-php/evas-web-storage
 */
namespace Evas\Web\Storage;

use Evas\Web\App as WebApp;
use Evas\Web\Storage\Cookie;
use Evas\Web\Storage\Session;

/**
 * Константы для свойств трейта.
 */
if (!defined('EVAS_COOKIE_CLASS')) define('EVAS_COOKIE_CLASS', Cookie::class);
if (!defined('EVAS_SESSION_CLASS')) define('EVAS_SESSION_CLASS', Session::class);


/**
 * Расширение класса приложения поддержкой cookie и session.
 * @author Egor Vasyakin <egor@evas-php.com>
 * @since 1.0
 */
trait AppWebStorageTrait
{
    /**
     * Установка имени класса cookie.
     * @param string
     * @return self
     */
    public static function setCookieClass(string $cookieClass)
    {
        return static::set('cookieClass', $cookieClass);
    }

    /**
     * Установка имени класса session.
     * @param string
     * @return self
     */
    public static function setSessionClass(string $sessionClass)
    {
        return static::set('sessionClass', $sessionClass);
    }

    /**
     * Получение имени класса cookie.
     * @param string
     * @return self
     */
    public static function getCookieClass(): string
    {
        if (!static::has('cookieClass')) {
            static::set('cookieClass', EVAS_COOKIE_CLASS);
        }
        return static::get('cookieClass');
    }

    /**
     * Получение имени класса session.
     * @param string
     * @return self
     */
    public static function getSessionClass(): string
    {
        if (!static::has('sessionClass')) {
            static::set('sessionClass', EVAS_SESSION_CLASS);
        }
        return static::get('sessionClass');
    }

	/**
     * Получение объекта cookie.
     * @return Cookie
     */
    public static function cookie(): object
    {
        if (!static::has('cookie')) {
            $cookieClass = static::getCookieClass();
            $cookie = new $cookieClass;
            if (get_called_class() == WebApp::class) {
                $cookie->setHost(static::getHost());
            }
            static::set('cookie', $cookie);
        }
        return static::get('cookie');
    }

    /**
     * Получение объекта session.
     * @return Session
     */
    public static function session(): object
    {
        if (!static::has('session')) {
            $sessionClass = static::getSessionClass();
            $session = new $sessionClass;
            static::set('session', $session);
        }
        return static::get('session');
    }
}
