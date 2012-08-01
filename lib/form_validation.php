<?php

class FormValidator {

    const REGEX_ALPHA = "/^[a-zA-Z ]*$/";
    const REGEX_DIGIT = "/^[0-9]*$/";
    const REGEX_ALPHANUM = "/^[0-9a-zA-Z ]*$/";

    /**
     * The array of errors (if any)
     * @var mixed 
     */
    private $errors;
    /**
     * The fields to be processed
     * @var mixed 
     */
    private $fields;
    /**
     * The value of the current field
     * @var mixed 
     */
    private $current_field;
    
    /**
     * Tackes and array of foelds and validayes them against specified rules
     * @param mixed $fields
     */
    public function __construct($fields = null) {
        if (!is_null($fields)) {
            $this->setFields($fields);
        }
    }

    /**
     * Runs the specified rules against the values
     * 
     * @return bool Succes of filure of the testes
     * @throws Exception
     */
    public function run() {
        foreach ($this->fields as $field_name => $field) {
            $this->current_field = $field;
            $rules = explode('|', $field['rules']);
            foreach ($rules as $rule) {
                if (preg_match('([^[]+)', $rule)) {//if the rule has [ or ] in it
                    $segments = explode('[', $rule);
                    $func = $segments[0];
                    if (isset($segments[1])) {
                        if ($this->endsWith($segments[1], ']')) {
                            $args = explode(',', substr_replace($segments[1], "", -1));
                        } else {
                            $args = explode(',', $segments[1]);
                        }
                        foreach ($args as &$arg) {
                            $arg = trim($arg);
                        }
                    }
                } else {
                    $func = $rule;
                }
                if (method_exists($this, $func)) {//call method in our class
                    if (isset($args)) {
                        call_user_func_array(array($this, $func), $args);
                    } else {
                        call_user_func(array($this, $func));
                    }
                } elseif (function_exists($func)) {//call php function
                    if (isset($args)) {
                        if(is_array($args)) {
                           array_push($args, $this->current_field['value']);
                        } else {
                            $args = array($args);
                        }
                        call_user_func_array($func, $args);
                    } else {
                        call_user_func($func, $this->current_field['value']);
                    }
                } else {
                    throw new Exception("Unknown method or function {$func}");
                }
            }
        }
        return empty($this->errors);
    }

    /**
     * Sets the array of fields to be used by the class
     * 
     * @param mixed $fields
     * @throws Exception
     */
    public function setFields($fields) {
        if (!is_array($fields) || empty($fields)) {
            throw new Exception("Please provide a valid fields array");
        } else {
            $this->fields = $fields;
        }
    }

    /**
     * Gets the array of errors.
     * 
     * @return array The array of the errors (if any)
     */
    public function getErrors() {
        return $this->errors;
    }

    /**
     * Returns true if the form has errors false on success
     * 
     * @return bool
     */
    public function hasErrors() {
        return !empty($this->errors);
    }

    /**
     * Valides the field as required (has a non-empty value)
     */
    protected function required() {
        if (strlen($this->current_field['value']) == 0) {
            $this->errors[] = "The field {$this->current_field['label']} is required";
        }
    }

    /**
     * Checks if the field is above a minimum length
     * 
     * @param int $length
     */
    protected function minLength($length) {
        if (strlen($this->current_field['value']) < $length) {
            $this->errors[] = "The field {$this->current_field['label']} has a minimum length of {$length}";
        }
    }

    /**
     * Checks if the field is below a maximim length
     * 
     * @param int $length
     */
    protected function maxLength($length) {
        if (strlen($this->current_field['value']) > $length) {
            $this->errors[] = "The field {$this->current_field['label']} has a minimum length of {$length}";
        }
    }

    /**
     * Checks to is the field in a given range.
     * 
     * @param int $min
     * @param int $max
     */
    protected function between($min, $max) {
        if (strlen($this->current_field['value']) < $min || strlen($this->current_field['value']) > $max) {
            $this->errors[] = "The field {$this->current_field['label']} must be between {$min} and {$max} characters long";
        }
    }

    /**
     * Checks if the field is a valid email
     */
    protected function isEmail() {
        $email = $this->current_field['value'];
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "The field {$this->current_field['label']} must be a valid email";
        }
    }

    /**
     * Checks if the field is a digit
     */
    protected function isDigit() {
        $value = $this->current_field['value'];
        if (!filter_var($value, FILTER_VALIDATE_INT)) {
            $this->errors[] = "The field {$this->current_field['label']} must be a digit";
        }
    }

    /**
     * Checks if the field is an integer
     */
    protected function isInt() {
        $value = $this->current_field['value'];
        if (!preg_match(self::REGEX_DIGIT, $value)) {
            $this->errors[] = "The field {$this->current_field['label']} must be an integer value";
        }
    }

    /**
     * Checks if the field is a letter of the latin alphabet
     */
    protected function isAlpha() {
        $value = $this->current_field['value'];
        if (!preg_match(self::REGEX_ALPHA, $value)) {
            $this->errors[] = "The field {$this->current_field['label']} must consist of letters only";
        }
    }

    /**
     * Checks if the field is a letter of the latin alphabet. 
     * The value may also contain digits
     */
    protected function isAlphaNum() {
        $value = $this->current_field['value'];
        if (!preg_match(self::REGEX_ALPHANUM, $value)) {
            $this->errors[] = "The field {$this->current_field['label']} must consist of letters and digits only";
        }
    }

    /**
     * Checks if a string -$haystack- starts with another sub-string -needle-
     * 
     * @param string $haystack The string on which the search will be performed
     * @param string $needle The (sub) string to be searched for
     * @return bool
     */
    public function startsWith($haystack, $needle) {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * Checks if a string -$haystack- ends with another sub-string -needle-
     * 
     * @param string $haystack The string on which the search will be performed
     * @param string $needle The (sub) string to be searched for
     * @return boolean
     */
    public function endsWith($haystack, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

}
