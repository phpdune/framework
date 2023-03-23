<?php

declare(strict_types=1);

namespace Dune\Session;

class SessionHandler
{
    /**
     * Session pattern regex
     *
     * @var const
     */
    protected const SESSION_PATTERN = '^[a-zA-Z0-9_\.]{1,64}$^';
    /**
     * Session Encrypter Instance
     *
     * @var SessionEncrypter
     */
    protected static SessionEncrypter $encrypter;

    /**
     * Setting Session
     *
     * @param  string  $key
     * @param string $value
     *
     * @return none
     */
    protected static function setSession(string $key, string $value): void
    {
        self::start();
        if (!isset($_SESSION[$key])) {
            if (self::sessionNameisValid($key)) {
                $value = config('session.encrypt') ? self::sessionEncrypt($value) : $value;
                $_SESSION[$key] = $value;
            }
        }
    }
    /**
     * Setting Session
     *
     * @param  string  $key
     * @param array $value
     *
     * @return none
     */
    protected static function setArraySession(string $key, array $value): void
    {
        self::start();
        if (!isset($_SESSION[$key])) {
            if (self::sessionNameisValid($key)) {
                  $_SESSION[$key] = $value;
                } 
                
            }
        }
    /**
     * getSession process goes here
     *
     * @param  string  $key
     *
     * @return string|null
     */
    protected static function getSession(string $key): mixed
    {
        self::start();
        if (isset($_SESSION[$key])) {
          if(is_array($_SESSION[$key]))
          {
            return $_SESSION[$key];
          }
            $getValue = config('session.encrypt') ? self::sessionDecrypt($_SESSION[$key]) : $_SESSION[$key];
            return $getValue;
        }
        return null;
    }

    /**
     * Check session name is a valid one by regex
     *
     * @param  string  $key
     *
     * @return bool
     */
    protected static function sessionNameisValid(string $key): bool
    {
        return (preg_match(self::SESSION_PATTERN, $key) === 1);
    }

    /**
     * Session encryption
     *
     * @param  string  $key
     *
     * @return string
     */
    protected static function sessionEncrypt(string $key): string
    {
         self::$encrypter = new SessionEncrypter();
        return self::$encrypter->encrypt($key);
    }
    /**
     * Session decryption
     *
     * @param  string  $key
     *
     * @return string
     */
    protected static function sessionDecrypt(string $key): string
    {
        self::$encrypter = new SessionEncrypter();
        return self::$encrypter->decrypt($key);
    }
     /**
     * set session_start() if it doesn't exist
     *
     * @param  string  none
     *
     * @return none
     */
    protected static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
           \session_name(config('session.session_name'));
           \session_set_cookie_params(config('session.lifetime'),config('session.path'),config('session.domain'),config('session.secure'),config('session.http_only'));
            \session_save_path(config('session.session_storage'));
            \session_start();
        }
    }
    /**
     * delete all session
     *
     * @param  string  none
     *
     * @return none
     */
    protected static function flushSession(): void
    {
       if (!session_status() == PHP_SESSION_NONE) {
        \session_unset();
        \session_destroy();
       }
    }
    /**
     * unset the session from given key
     *
     * @param  string  $key
     *
     * @return string
     */
    protected static function unsetSession(string $key): void
    {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    /**
     * return all current session
     *
     * @param  string none
     *
     * @return null|array
     */
    protected static function getAllSession(): ?array
    {
        self::start();
        self::$encrypter = new SessionEncrypter();
        $data = [];
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                $data[$key] = self::sessionDecrypt($value);
            }
            return $data;
        }
        return null;
    }
    /**
     * check value exist by session key
     *
     * @param  string  $key
     *
     * @return bool
     */
    protected static function sessionHas(string $key): bool
    {
        self::start();
        if (isset($_SESSION[$key])) {
            return true;
        }
        return false;
    }
}
