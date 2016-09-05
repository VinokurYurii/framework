<?php

namespace Yurii\Security\Model;
/**
 * Interface UserInterface
 * @package Yurii\Security\Model
 */

interface UserInterface {
    /**
     * @return string $role
     */
    function getRole();
}