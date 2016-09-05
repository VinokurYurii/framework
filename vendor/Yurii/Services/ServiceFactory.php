<?php

namespace Yurii\Services;

use Yurii\Exception\ServiceException;

class ServiceFactory {

    private static $prefix = 'Yurii\\Services\\';

    public static function get($service) {
        $service = self::$prefix . ucfirst($service) . 'Service';

        if (class_exists($service)) {
            return $service::getInstance();
        }
        else {
            return Container::getInstance();
        }
    }
}