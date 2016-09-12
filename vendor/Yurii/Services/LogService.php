<?php

namespace Yurii\Services;

class LogService implements ServiceInterface {
    private static $instance;
    private static $num = 0;
    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    public function addLog($log, $logLevel = 'info') {
        self::$num += 1;
        $path = __DIR__ . '/../../../tmp/log.txt';
        $time = date("m.d.y H:i:s");

        file_put_contents($path, $time . ' / ' . $logLevel . ' / ' . $log . ' || ' . self::$num . "\n", FILE_APPEND);
    }
}