<?php

namespace Yurii\Services\DB;

use Yurii\Exception\DatabaseException;

class Postgresql implements ConnectionInterface {
    use ConnectionTrait;
    private static $connection;

    public static function getConnection($connectionData) {
        if (empty(self::$connection)){
            if (!empty($connectionData && is_array($connectionData))) {
                if ($connectionData['host'] == 'localhost') {
                    $connectionData['host'] = '127.0.0.1';
                }
                try {
                    self::$connection = pg_connect('host=' . $connectionData['host'] . ' port=' . $connectionData['port']
                        . ' dbname=' . $connectionData['database'] . ' user=' . $connectionData['user']
                        . ' password=' . $connectionData['password']);
                } catch (\Exception $e) {
                    throw new DatabaseException($e->getMessage());
                }
            }
        }
        return self::$connection;
    }

    public function fetchObject($objectName) {
        foreach ($this->queryResult as $result) {
            pg_fetch_object($result, $objectName);
        }
    }
}