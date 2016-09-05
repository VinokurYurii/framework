<?php

namespace Yurii\Exception;
/**
 * Class ServiceException
 * @package Yurii\Exception
 *
 * most worse exception called when all is bad
 */
class ServiceException extends MainException {
    protected $type = 'error';
}