<?php

class Loader {
    private static $instance   = null;
    private static $namespaces = array();

    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getNamespaces() {
        return self::$namespaces;
    }

    public static function load($classname) { //load namespaces
        $path = '';

        foreach(self::$namespaces as $namespace => $nsRootPath) { //checking self::$namespaces for matching
            if(preg_match("/^" . $namespace . "/", $classname)) {
                $path =  $namespace . substr($classname, strlen($namespace));
                $path = $nsRootPath . str_replace("\\", "/", $path) . '.php';
                break;
            }
        }

        if(empty($path)) { //if self::$namespace haven't matches search in framework
            $path = str_replace('Application', '', $classname);
            $path = __DIR__ . str_replace("\\", "/", $path) . '.php';
        }

        if(!file_exists($path)){
            throw new Exception('File: ' . $classname . ' does not exists. path: ' . $path);
        }

        include_once($path);
    }

    private function __construct() {
        spl_autoload_register(array(__CLASS__, 'load'));
    }

    private function __clone() { /* lock */ }

    public static function addNamespacePath($namespace, $nsRootPath) { //add additional namespaces
        if(!preg_match("/\/$/", $nsRootPath)) { //checking slash at the end of path and add it, if it need
            $nsRootPath .= '/';
        }
        self::$namespaces[$namespace] = $nsRootPath;
    }
}

Loader::getInstance();