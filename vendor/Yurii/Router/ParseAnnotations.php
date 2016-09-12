<?php

namespace Yurii\Router;

use Yurii\Services\ServiceFactory;

class ParseAnnotations {
    private static $namespaces = array();

    public static function getRoutes() {
        self::$namespaces = ServiceFactory::get('config')->getConfig('namespaces');

        $path = realpath(__DIR__ . '/../../../src/');
        $controllerAppDir = realpath(__DIR__ . '/../../../App/Controllers');
        $dirs  = scandir($path);
        $bundles = array();
        foreach($dirs as $dir) {
            if ($dir != '.' && $dir != '..') {
                $bundles[] = $dir;
            }
        }

        $controllers = array();

        foreach ($bundles as $bundle) {
            $controllerDir = $path . '/' . $bundle . '/Controllers/';
            if (file_exists($controllerDir)) {
                if (is_dir($controllerDir)) {
                    $files = scandir($controllerDir);

                    foreach($files as $file) {
                        if ($file != '.' && $file != '..') {
                            if (preg_match("/Controller.php$/", $file)) {
                                $controllers[] = $controllerDir . $file;
                            }
                        }
                    }
                }
            }
        }

        if (file_exists($controllerAppDir)) {
            if (is_dir($controllerAppDir)) {
                $files = scandir($controllerAppDir);

                foreach($files as $file) {
                    if ($file != '.' && $file != '..') {
                        if (preg_match("/Controller.php$/", $file)) {
                            $controllers[] = $controllerAppDir . '/' . $file;
                        }
                    }
                }
            }
        }

        $contrNamespaces = array_map('self::getNamespaces', $controllers);

        $rawAnnotations = array();
        foreach($contrNamespaces as $cns) {
            $controllerReflication = new \ReflectionClass($cns);
            $rawAnnotations[$controllerReflication->getName()] = array();
            foreach($controllerReflication->getMethods(\ReflectionMethod::IS_PUBLIC) as $method) {
                preg_match_all("~\@Route\(([\w\W]+)\\)~", $method->getDocComment(), $annotation);
                $rawAnnotations[$controllerReflication->getName()][$method->getName()] = $annotation[1];
            }
        }

        $routes = self::createRoutesArray($rawAnnotations);
        return $routes;
    }

    private static function getNamespaces($path) {

        $frameworkDir = realpath(__DIR__ . '/../../../');
        $path = str_replace($frameworkDir . '/', '', $path);
        $path = str_replace('.php', '', $path);

        foreach (self::$namespaces as $name => $dir) {
            if(preg_match('~' . $dir . $name . '~', $path)) {
                $path = str_replace($dir, '', $path);
            }
        }
        return str_replace('/', '\\', $path);
    }

    private static function createRoutesArray($rawAnnotation) {
        $routesArray = array();

        foreach($rawAnnotation as $controller => $actions) {

            foreach($actions as $actionName => $annotation) {
                if( preg_match('/Action$/', $actionName) && !empty($annotation)) {
                    $params = self::parseAnnotations($annotation[0]);
                    if(isset($params['name']) && !empty($params['name'])) {
                        $name = $params['name'];
                        unset($params['name']);
                        $routesArray[$name] = $params;
                        $routesArray[$name]['controller'] = $controller;
                        $routesArray[$name]['action'] = str_replace('Action', '', $actionName);
                    }
                }
            }
        }
        return $routesArray;
    }

    private static function parseAnnotations($rawAnnotation) {

        $annotation = trim(str_replace('*', '', $rawAnnotation));
        $rawParams = array();

        if(preg_match('/\[|\]/', $annotation)) {
            $rawParams = preg_split('/,(?=[^\]]+\[)/U', $annotation);
        } else {
            $rawParams = explode(',', $annotation);
        }
        $rawParams = array_map('trim', $rawParams);

        $params = array();

        foreach ($rawParams as $rawParam) {
            if (!empty($rawParam)) {
                if (preg_match("~^([\w]+) ?= ?\[([\w\W]+)\]$~", $rawParam, $matches)) {
                    $params[$matches[1]] = array();
                    $matches[2] = trim($matches[2]);
                    if(!empty($matches[2])) {
                        $subParams = explode(',', $matches[2]);
                        foreach($subParams as $subParam) {
                            if (preg_match("~^([\w]+) ?= ?([\w\W]+)$~", trim($subParam), $subParamsMatches)) {
                                $params[$matches[1]][$subParamsMatches[1]] = $subParamsMatches[2];
                            }
                        }
                    }
                }
                else if (preg_match("~^([\w]+) ?= ?([\w\W]+)$~", $rawParam, $matches)) {
                    $params[$matches[1]] = $matches[2];
                }
            }
        }
        return $params;
    }

}