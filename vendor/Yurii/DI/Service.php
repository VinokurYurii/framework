<?php

namespace Yurii\DI;

use Yurii\Exception\ServiceException;

/**
 * Class Service
 * @package Yurii\DI
 *
 * Fabric object for services
 */
class Service {

    public static $services = array('security', 'session', 'router', 'db', 'config');

    private static $prefix = 'Yurii\\DI\\';

    /**
     * @param $service
     * @return SERVICE $instance
     * @throws ServiceException
     */
    public static function get($service) {
        foreach(self::$services as $serv) {
            if(strtolower($service) == $serv) {
                $service = self::$prefix . ucfirst($service) . 'Service';
                return $service::getInstance();
            }
        }
        throw new ServiceException(501);
    }
}