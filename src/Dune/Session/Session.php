<?php

declare(strict_types=1);

namespace Dune\Session;

use Dune\Session\SessionInterface;
use Dune\Session\SessionEncrypter;
use Dune\Session\Config\SessionConfig;

class Session implements SessionInterface
{
    use SessionContainer;

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
     * SessionConfig Instance
     *
     * @var SessionConfig
     */
    protected SessionConfig $config;

    /**
     * session encrypter instance setting
     */
    public function __construct(SessionEncrypter $encrypter, SessionConfig $config)
    {
        $this->encrypter = $encrypter;
        $this->config = $config;
        $this->start();
    }

    /**
     * Setting Session
     *
     * @param  string  $key
     * @param string|array $value
     *
     * @return mixed
     */
    public function set(string $key, array|string $value): mixed
    {
        if(is_array($value)) {
            return $this->setArraySession($key, $value);
        }
        if ($this->sessionNameisValid($key)) {
            if ($this->config->get('encrypt') && $key != '_token') {
                $value = $this->sessionEncrypt($value);
            }
            $_SESSION[$key] = $value;
        }
        return null;
    }
    /**
     * Setting Array Session
     *
     * @param  string  $key
     * @param array<mixed> $values
     *
     */
    private function setArraySession(string $key, array $values): void
    {
        $data = [];
        if ($this->sessionNameisValid($key)) {
            if ($this->config->get('encrypt')) {
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
     * @return mixed
     */
    public function get(string $key): mixed
    {
        if (isset($_SESSION[$key])) {
            if (is_array($_SESSION[$key])) {
                return $this->getArraySession($key);
            }
            $getValue = $this->config->get('encrypt') && $key != '_token' ? $this->sessionDecrypt($_SESSION[$key]) : $_SESSION[$key];
            return $getValue;
        } elseif (isset($_SESSION['__'.$key])) {
            $value = $_SESSION['__'.$key];
            $this->unset('__'.$key);
            return $value;
        }
        return null;
    }
    /**
     * getting Array Session
     *
     * @param  string  $key
     *
     * @return array<mixed>|null
     */
     private function getArraySession($key): ?array
     {
         $data = [];
         $values = $_SESSION[$key];
         if ($this->config->get('encrypt')) {
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
     */
    protected function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            \session_name($this->config->get('name'));
            \session_set_cookie_params($this->config->get('lifetime'), $this->config->get('path'), $this->config->get('domain'), $this->config->get('secure'), $this->config->get('http_only'));
            \session_save_path($this->config->get('storage'));
            \session_start();
        }
    }
    /**
     * delete all session
     *
     */
    public function flush(): void
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
     */
    public function unset(string $key): void
    {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    /**
     * return all current session
     *
     * @return null|array<mixed>
     */
    public function all(): ?array
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
    public function has(string $key): bool
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
     */
    public function overwrite(string $key, string $value): void
    {
        (!$this->has($key) ? $this->set($key, $value) : $_SESSION[$key] = $value);
    }
    /**
     * get the session id
     *
     * @return ?tring|int
     */
    public function id(): string|int
    {
        return \session_id();
    }
    /**
     * get the session name
     *
     * @return ?string
     */
    public function name(): ?string
    {
        return \session_name()();
    }
}
