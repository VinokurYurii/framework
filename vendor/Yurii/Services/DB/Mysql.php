<?php

namespace Yurii\Services\DB;

use Yurii\Exception\DatabaseException;

class Mysql implements ConnectionInterface {
    use ConnectionTrait;

    private static $connection;
    private static $instance;
    private $result;

    public static function getConnection($connectionData) {
        if (empty(self::$instance)){
            if (!empty($connectionData && is_array($connectionData))) {
                if ($connectionData['host'] == 'localhost') {
                    $connectionData['host'] = '127.0.0.1';
                }
                self::$instance = new self();
                try {
                    self::$connection = new \mysqli($connectionData['host'], $connectionData['user'],
                        $connectionData['password'], $connectionData['database'], $connectionData['port']);
                } catch (\Exception $e) {
                    throw new DatabaseException($e->getMessage());
                }
            }
        }
        return self::$instance;
    }

    public function fetchObject($objectName) {
        $objects = array();

        while ($obj = $this->result->fetch_object($objectName)){
            $objects[] = $obj;
        }

        return $objects;
    }

    public function query($query) {
        $this->result = self::$connection->query($query);
    }
}