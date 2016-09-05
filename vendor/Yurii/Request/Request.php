<?php

namespace Yurii\Request;

use Yurii\DI\Service;
use Yurii\Exception\AuthRequredException;
use Yurii\Exception\SecurityException;

/**
 * Class Request
 * @package Yurii\Request
 */
class Request {
    private $allowedPath = array('/login', '/signin');//contain list where not need authenticated

    /**
     * @return bool
     * @throws AuthRequredException
     * @throws SecurityException
     * @throws \Yurii\Exception\ServiceException
     *
     * check request method and checked some secure param
     */
    function isPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return false;
        }
        else {
            if (!Service::get('security')->isTokenMatch()) {
                throw new SecurityException('CSRF atack');
            }
            if (!Service::get('security')->isAuthenticated() && !in_array($_SERVER['REQUEST_URI'], $this->allowedPath)) {
                throw new AuthRequredException('You don\'t logined user. Please Log in os sign in.');
            }
            return true;
        }
    }

    /**
     * @param $field
     * @return mixed
     *
     * secure incoming post data
     */
    function post($field) {
        return $this->secureData($_POST[$field]);
    }

    function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * @param $data
     * @return mixed
     *
     * secure incoming data
     */
    private function secureData($data) {
        return filter_var(htmlspecialchars($data), FILTER_SANITIZE_STRING); // Removes tags and removes special characters or codes, if necessary.
    }
};