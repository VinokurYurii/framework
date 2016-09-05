<?php

namespace Yurii\Services;

use Yurii\Services\DB;
use Yurii\Exception\DatabaseException;

class DbService {
    private static $connection;
    private static $instance;
    private static $dbEnginePath = 'Yurii\\Services\\DB\\';

    public static function getConnection() {
        if (empty(self::$connection)) {
            $connectionConfig = ServiceFactory::get('config')->getConfig('database');
            if (empty($connectionConfig)) {
                throw new DatabaseException('Cant connect to DataBase without full connection info.');
            }

            if (!empty($connectionConfig['engine'])) $connectionConfig['engine'] = 'mysql';

            $dbClass = self::$dbEnginePath.ucfirst(strtolower($connectionConfig['engine']));
            if (class_exists($dbClass)) {
                self::$connection = $dbClass::getConnection($connectionConfig);
            }
        }
        return self::$connection;
    }

    private function __construct() {/* lock */}
    private function __clone() {/* lock */}

    public static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}