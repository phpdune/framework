<?php

declare(strict_types=1);

namespace Dune\Http;

class Request implements RequestInterface
{
    /**
      * All data from $_REQUEST and $_SERVER
      *
      * @var array
      */
    private array $data = [];
    /**
      * Validation error message storred here
      *
      * @var array
      */
    private array $errors = [];
    /**
      * old value of form fields stored here
      *
      * @var array
      */
    private array $oldInputs = [];
    /**
      * route params
      *
      * @var array
      */
    public array $params = [];
    /**
     * All data from $_GET and $_POST will stored from this constructer
     *
     * @param  none
     *
     * @return none
     */
    public function __construct()
    {
        if ($this->isGet()) {
            foreach ($_GET as $key => $value) {
                $this->data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if ($this->isPost()) {
            foreach ($_POST as $key => $value) {
                $this->data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }
    /**
     * @param string $key
     * @param null $default
     *
     * @return string|null
     */
    public function get($key, $default = null): ?string
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }
    /**
     *
     * @param  none
     *
     * @return array
     */
    public function all(): array
    {
        return $this->data;
    }
     /**
     * @param  none
     *
     * @return string
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

     /**
     * @param  none
     *
     * @return string
     */
     public function server($key): ?string
     {
         $value = $_SERVER[strtoupper($key)] ?? null;
         return $value;
     }
     /**
     * @param  none
     *
     * @return bool
     */
     public function isGet(): bool
     {
         if ($this->method() == 'GET') {
             return true;
         }
         return false;
     }
    /**
     *
     * @param  none
     *
     * @return bool
     */
     public function isPost(): bool
     {
         if ($this->method() == 'POST') {
             return true;
         }
         return false;
     }
     /**
     * @param  none
     *
     * @return string
     */
     public function getHeaders(string $uri): string
     {
         return get_headers($uri);
     }
     /**
     * @param  none
     *
     * @return string
     */
     public function getIp(): string
     {
         return $this->server('remote_addr');
     }
    /**
     * @param  array|object $rules
     *
     * @return none|null
     */
     public function validate(array|object $data)
     {
         if (is_array($data)) {
             $rules = $data;
         } else {
             $rules = $data->validation();
         }
         foreach ($rules as $field => $rule) {
             foreach ($rule as $ruleName => $ruleValue) {
                 if ($this->get($field)) {
                     $this->oldInputs['old_'.$field] = $this->get($field);
                 }

                 ($ruleName ? $this->checkValidation($ruleName, $ruleValue, $field) : $this->checkValidation($ruleValue, $ruleValue, $field));
             }
         }
         if (!empty($this->errors)) {
             if (!$this->oldInputs) {
                 return redirect()->back()->withArray($this->errors);
             }
             dd($this->oldInputs);
             return redirect()->back()->withArray(array_merge($this->errors, $this->oldInputs));
         }
     }
    /**
     * @param  string $name
     * @param mixed $ruleValue
     * @param string $field
     *
     * @return string
     */
     public function checkValidation(string $name, mixed $ruleValue, string $field): void
     {
         $value = $this->get($field) ?? '';
         if ($name == 'required') {
             if (!$value) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = "field is required";
                 }
             }
         } elseif ($name == 'min') {
             if (strlen($value) < $ruleValue) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = "field must be at least {$ruleValue} characters";
                 }
             }
         } elseif ($name == 'max') {
             if (strlen($value) > $ruleValue) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = "field may not be greater than {$ruleValue} characters";
                 }
             }
         } elseif ($name == 'email') {
             if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = "field must be a valid email address";
                 }
             }
         } elseif ($name == 'equal') {
             if ($value !== $this->get($ruleValue)) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = "field must be equal to {$ruleValue}";
                 }
             }
         } elseif ($name == 'numeric') {
             if (!ctype_digit($value)) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = 'field must be numeric';
                 }
             }
         } elseif ($name == 'digit') {
             $num = explode(',', $ruleValue);
             if ($value < $num[0] || $value > $num[1]) {
                 if (!$this->errors[$field]) {
                     $this->errors[$field] = "field must be digit between {$num[0]} to {$num[1]}";
                 }
             }
         }
     }
    /**
     * @param  string $key
     * @param mixed $value
     *
     * @return none
     */
     public function setParams(array $params): void
     {
         $this->params = $params;
     }
    /**
     * @param  string $key
     *
     * @return none
     */
     public function param(string $key): mixed
     {
         return (isset($this->params[$key]) ? $this->params[$key] : null);
     }
}
