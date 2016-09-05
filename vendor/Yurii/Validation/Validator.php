<?php

namespace Yurii\Validation;
/**
 * Class Validator
 * @package Yurii\Validation
 */
class Validator {

    private $errors;
    protected $validationObject;

    public function __construct($validationObject) {
        $this->validationObject = $validationObject;
    }

    function getErrors() {
        return $this->errors;
    }

    /**
     * @return bool
     *
     * checked value for matching parameters
     */
    public function isValid(){
        $fields = $this->validationObject->getFields();//getting field of validation object
        $all_rules = $this->validationObject->getRules();//getting rules for validation object with validator object(s)

        foreach($all_rules as $name => $rules){
            if(array_key_exists($name, $fields)){
                foreach($rules as $rule){
                    if ($rule->isValid($fields[$name]) !== true) {
                        $this->errors[$name] = ucfirst($name) . ': ' . $rule->isValid($fields[$name]);
                    }
                }
            }
        }

        return empty($this->errors);
    }
}