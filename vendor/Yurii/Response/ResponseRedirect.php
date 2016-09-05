<?php

namespace Yurii\Response;

use Yurii\DI\Service;

class ResponseRedirect extends Response {

    public function __construct($path, $message = '') {
        if (!empty($message)) {
            Service::get('session')->addFlush('info', $message);
        }
        parent::setHeader('Location', $path);
    }
}