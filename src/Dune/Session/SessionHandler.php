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
        self::sessionStart();
        self::$encrypter = new SessionEncrypter();
        if (!isset($_SESSION[$key])) {
            if (self::sessionNameisValid($key)) {
                $value = self::sessionEncrypt($value);
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
    protected static function getSession(string $key): ?string
    {
        self::$encrypter = new SessionEncrypter();
        if (isset($_SESSION[$key])) {
            $getValue = self::sessionDecrypt($_SESSION[$key]);
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
        return self::$encrypter->decrypt($key);
    }
     /**
     * set session_start() if it doesn't exist
     *
     * @param  string  none
     *
     * @return none
     */
    protected static function sessionStart(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
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
        $_SESSION = [];
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
        if (isset($_SESSION[$key])) {
            return true;
        }
        return false;
    }
}
