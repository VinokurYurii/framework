<?php

namespace Yurii\Services\DB;

interface ConnectionInterface {
    const DATETIME_FORMAT = "d.m.Y H:i:s";

    public static function getConnection($connectionData);

    public function fetchObject($objectName);
    public function query($query);
}