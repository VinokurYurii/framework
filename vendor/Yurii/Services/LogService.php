<?php

namespace Yurii\Services;

class LogService implements ServiceInterface {
    private static $instance;
    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function addLog($log, $logLevel = 'info') {
        $path = __DIR__ . '/../../log/log.txt';
        if(!file_exists($path)) {
            fopen($path, 'w');
        }
        $time = date("m.d.y H:i:s");
        $fp = fopen($path, 'a');
        fwrite($fp, $time . ' / ' . $logLevel . ' / ' . $log . "\n");
        fclose($fp);
    }
}