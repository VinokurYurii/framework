<?php

namespace Yurii\Services;

class ConfigService implements ServiceInterface {
    private static $instance;
    private static $config;

    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConfig($prop = null) {
        if(is_null($prop)) {
            return self::$config;
        }
        else if(array_key_exists($prop, self::$config)) {
            return self::$config[$prop];
        }
        else {
            return null;
        }
    }

    public function setConfig($config) {
        self::$config = $config;
    }
}