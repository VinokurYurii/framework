<?php

namespace Yurii\Services;

class Container implements ServiceInterface {
    private static $sorage = array();

    public static function getInstance() {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function addToStorage($service, $entity, $value) {
        self::$sorage[$service][$entity] = $value;
    }

    public function getFromStorage($service, $entity) {
        if (!empty(self::$sorage[$service])) {
            if (!empty(self::$sorage[$service][$entity])) {
                return self::$sorage[$service][$entity];
            }
        }
        return null;
    }
}