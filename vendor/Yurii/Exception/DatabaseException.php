<?php

namespace Yurii\Exception;

use Yurii\DI\Service;
use Yurii\Renderer\Renderer;
use Yurii\Response\Response;

/**
 * Class DatabaseException
 * @package Yurii\Exception
 */
class DatabaseException extends MainException {
    protected $type = 'warning';
}