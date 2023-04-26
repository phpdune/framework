<?php

declare(strict_types=1);

namespace Dune\Http;

use Dune\Http\Request;
use Dune\Http\ValidaterInterface;
use Dune\Helpers\Redirect;

class Validater
{
    /**
     * request instance
     *
     * @var Request
     */
    private ?Request $request = null;
    /**
     * Validation error message storred here
     *
     * @var array<string,string>
     */
    private array $errors = [];
    /**
      * old value of form fields stored here
      *
      * @var array<string,mixed>
      */
    private array $oldInputs = [];
    /**
     * dependency setting
     *
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    /**
    * request validation
    * request input validation
    *
    * @param ValidaterInterface|array<mixed> $data
    *
    * @return ?Redirect
    */
    public function validate(ValidaterInterface|array $data): ?Redirect
    {
        if (is_array($data)) {
            $rules = $data;
        } else {
            $rules = $data->validation();
        }
        foreach ($rules as $field => $rule) {
            foreach ($rule as $ruleName => $ruleValue) {
                if ($this->request->get($field)) {
                    $this->oldInputs['old_'.$field] = $this->request->get($field);
                }

                ($ruleName ? $this->checkValidation($ruleName, $ruleValue, $field) : $this->checkValidation($ruleValue, $ruleValue, $field));
            }
        }
        if (!empty($this->errors)) {
            if (!$this->oldInputs) {
                return redirect()->back()->withArray($this->errors);
            }

            return redirect()->back()->withArray(array_merge($this->errors, $this->oldInputs));
        }
        return null;
    }
    /**
    * @param  string $name
    * @param mixed $ruleValue
    * @param string $field
    *
    */
    private function checkValidation(string $name, mixed $ruleValue, string $field): void
    {
        $value = $this->request->get($field) ?? '';
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
            if ($value !== $this->request->get($ruleValue)) {
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
}
