<?php

namespace Yurii\Validation\Filter;
/**
 * Class NotBlank
 * @package Yurii\Validation\Filter
 */
class NotBlank {
    /**
     * @param $value
     * @return bool|string
     *
     * checked value for matching parameters
     */
    public function isValid($value){
        return empty($value) ? 'must be not blank': true;
    }
}