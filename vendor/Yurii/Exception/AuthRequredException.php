<?php

namespace Yurii\Exception;

use Yurii\DI\Service;

/**
 * Class AuthRequredException
 * @package Yurii\Exception
 */

class AuthRequredException extends MainException {
    protected $type = 'warning';
    protected $beforeSolve = true;
    protected $redirectAddress = '/login';

    /**
     * @throws ServiceException
     *
     *save request uri in session for redirect after login
     */
    protected function beforeSolveException() {
        if (isset($_SERVER['REQUEST_URI'])) {
            Service::get('session')->returnUrl = $_SERVER['REQUEST_URI'];
        }
    }
}