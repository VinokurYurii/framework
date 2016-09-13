<?php

namespace Yurii\Controller;

use Yurii\Exception\HttpNotFoundException;
use Yurii\Services\ServiceFactory;
use Yurii\Response\Response;
use Yurii\Response\ResponseRedirect;
use Yurii\Request\Request;
use Yurii\Renderer\Renderer;
use Yurii\Router\Router;

/**
 * Class Controller
 * @package Yurii\Controller
 *
 * main abstract controller class
 */
abstract class Controller {
    public $title, $content, $date, $router;

    /**
     * @return Request object
     */
    function getRequest() {
        return new Request();
    }

    /**
     * @param $layout
     * @param array $data
     * @return Response object
     *
     * render data with layout
     */
    function render($layout, $data = array()) {

        if (!file_exists($layout)) {
            if (isset($data['src'])) {
                $fullPath = $this->handleViewPath($layout, $data['src']);
            }
            else {
                $fullPath = $this->handleViewPath($layout);
            }
        } else {
            $fullPath = $layout;
        }
        $renderer = $this->getRenderer(get_class($this));
        $content = $renderer::render($fullPath, $data);
        return new Response($content);
    }

    /**
     * @param $shortPath
     * @param array $src
     * @return string
     *
     * processed path and return full path to view
     */
    protected function handleViewPath($shortPath, $src = array()) { //find path to view
        $app_path = ServiceFactory::get('config')->getConfig('app_path');
        $map = ServiceFactory::get('config')->getConfig('namespaces');

        $full_path = '';
        if (empty($src)) {// if we haven't special modifications for view
            foreach($map as $namespace => $dir) {//looking namespace in namespaces map
                $class = get_class($this);
                if(preg_match('/^' . $namespace . '/', $class)) {
                    //construct path based on controller namespace
                    $path = $app_path . '/' . $dir . preg_replace('/Controller$/', '', str_replace("\\", '/', $class));
                    $full_path =  preg_replace('/Controllers/', 'Views', $path) . '/' . $shortPath . '.php';
                    break;
                }
            }
        }
        else {
            foreach($map as $namespace => $dir) {//looking namespace in namespaces map
                if($namespace == $src['src']) {
                    //construct path based on controller namespace
                    $full_path = $app_path . '/' . $dir . $namespace . '/Views/' . $src['controller'] . '/' . $shortPath . '.php';
                    break;
                }
            }
        }

        $full_path = str_replace('/', DIRECTORY_SEPARATOR, $full_path);
        if(!file_exists($full_path)) {
            throw new HttpNotFoundException('file ' . $shortPath . '(' . $full_path . ')' . ' doesn\'t exist');
        }
        return $full_path;

    }

    protected function getRenderer($mainTamplateFile = '') {
        return empty($mainTamplateFile) ? new Renderer() : new Renderer($mainTamplateFile);
    }

    /**
     * @param $path
     * @param $msg
     * @return ResponseRedirect object
     */
    function redirect($path, $msg) {
        return new ResponseRedirect($path, $msg);
    }

    /**
     * @param $name
     * @param array $data
     * @return processed path
     * @throws \Yurii\Exception\ServiceException
     *
     * delegate RouteServise
     */
    function generateRoute($name, $data = array()) {
        if (empty($this->router)) {
            $this->router = Router::getInstance();
        }
        return $this->router->generateRoute($name, $data);
    }

}