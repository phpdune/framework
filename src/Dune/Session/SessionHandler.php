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
    protected SessionEncrypter $encrypter;

    /**
     * @param  \Dune\Session\SessionEncrypter
     *
     * @return none
     */
    public function __construct(SessionEncrypter $encrypter)
    {
        $this->encrypter = $encrypter;
        $this->start();
    }

    /**
     * Setting Session
     *
     * @param  string  $key
     * @param string $value
     *
     * @return none
     */
    public function setSession(string $key, string $value): void
    {
        if ($this->sessionNameisValid($key)) {
            if (config('session.encrypt') && $key != '_token') {
                $value = $this->sessionEncrypt($value);
            }
            $_SESSION[$key] = $value;
        }
    }
    /**
     * Setting Array Session
     *
     * @param  string  $key
     * @param array $value
     *
     * @return none
     */
    public function setArraySession(string $key, array $values): void
    {
        $data = [];
        if ($this->sessionNameisValid($key)) {
            if (config('session.encrypt')) {
                foreach ($values as $vkey => $value) {
                    $data[$vkey] = $this->sessionEncrypt($value);
                }
            }
            $value = (($data) ? $data : $values);
            $_SESSION[$key] = $value;
        }
    }
    /**
     * getSession process goes here
     *
     * @param  string  $key
     *
     * @return string|null
     */
    public function getSession(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            if (is_array($_SESSION[$key])) {
                return $this->getArraySession($key);
            }
            $getValue = config('session.encrypt') && $key != '_token' ? $this->sessionDecrypt($_SESSION[$key]) : $_SESSION[$key];
            return $getValue;
        } elseif (isset($_SESSION['__'.$key])) {
            $value = $_SESSION['__'.$key];
            $this->unsetSession('__'.$key);
            return $value;
        }
        return null;
    }
    /**
     * getting Array Session
     *
     * @param  string  $key
     *
     * @return array|null
     */
     private function getArraySession($key): ?array
     {
         $data = [];
         $values = $_SESSION[$key];
         if (config('session.encrypt')) {
             foreach ($values as $vkey => $value) {
                 $data[$vkey] = $this->sessionDecrypt($value);
             }
             return $data;
         }
         return $values;
     }
    /**
     * Check session name is a valid one by regex
     *
     * @param  string  $key
     *
     * @return bool
     */
    protected function sessionNameisValid(string $key): bool
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
    protected function sessionEncrypt(string $key): string
    {
        return $this->encrypter->encrypt($key);
    }
    /**
     * Session decryption
     *
     * @param  string  $key
     *
     * @return string
     */
    protected function sessionDecrypt(string $key): string
    {
        return $this->encrypter->decrypt($key);
    }
     /**
     * set session_start() if it doesn't exist
     *
     * @param  string  none
     *
     * @return none
     */
    protected function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            \session_name(config('session.session_name'));
            \session_set_cookie_params(config('session.lifetime'), config('session.path'), config('session.domain'), config('session.secure'), config('session.http_only'));
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
    public function flushSession(): void
    {
        if (session_status() != PHP_SESSION_NONE) {
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
    public function unsetSession(string $key): void
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
    public function getAllSession(): ?array
    {
        $data = [];
        if (isset($_SESSION)) {
            foreach ($_SESSION as $key => $value) {
                $data[$key] = $this->sessionDecrypt($value);
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
    public function sessionHas(string $key): bool
    {
        if (isset($_SESSION[$key]) || isset($_SESSION['__'.$key])) {
            return true;
        }
        return false;
    }
     /**
     * will add new value to the current session
     *
     * @param string $key
     * @param string $value
     *
     * @return bool|null
     */
    public function sessionOverwrite(string $key, string $value): void
    {
        (!$this->sessionHas($key) ? $this->setSession($key, $value) : $_SESSION[$key] = $value);
    }
     /**
     * return $_SESSION
     *
     * @param none
     *
     * @return array|null
     */
    public function allSession(): ?array
    {
        return (empty($_SESSION) ? null : $_SESSION);
    }
}
