<?php

namespace Yurii\Controller;

use Yurii\DI\Service;
use Yurii\Response\Response;
use Yurii\Response\ResponseRedirect;
use Yurii\Request\Request;
use Yurii\Renderer\Renderer;

/**
 * Class Controller
 * @package Yurii\Controller
 *
 * main abstract controller class
 */
abstract class Controller {
    public $title, $content, $date;

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

        $renderer = $this->getRenderer();
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

        if (empty($src)) {
            $path = preg_replace('/Controller$/', '', str_replace("\\", '/', get_class($this))); // create path to view
            $path = preg_replace('/Controller/', 'views', $path) . '/' . $shortPath . '.php';
        }
        else {
            $path = preg_replace('#/[\w]+Controller$#', '/' . $src['controller'], str_replace("\\", '/', get_class($this))); // create path to view using src data
            $path = preg_replace('/Controller/', 'views', $path) . '/' . $shortPath . '.php';
            $path = preg_replace('#^[\w]+?/#', '/' . $src['src'] . '/', $path);
        }

        if (preg_match('/^Yurii/', $path)) {
            $path = preg_replace('/^Yurii/', '/framework', $path);
        } else {
            $path = '/src/' . $path;
        }

        $dir = realpath(__DIR__ . '/../../'); //get real path to framework directory
        return $dir . $path; // full path to wiew

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
        return Service::get('router')->generateRoute($name, $data);
    }

}