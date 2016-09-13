<?php

namespace Yurii\Renderer;

use Yurii\Services\ServiceFactory;
use Yurii\Router\Router;
use Yurii\Exception\ServiceException;
use Yurii\Exception\HttpNotFoundException;
use Yurii\Response\Response;

/**
 * Class Renderer
 * @package Yurii\Renderer
 */
class Renderer {

    protected static $main_layout = ''; //keep path to main template

    public function __construct($main_layout_file = '') {

        if (empty(self::$main_layout)) {
            if(preg_match('/(.php|.html)$/', $main_layout_file)) {//if file ending
                if (empty($main_layout_file)) {
                    self::$main_layout = $this->findMainLayoutByControllerNamespace($main_layout_file, true);
                } else {
                    self::$main_layout = $main_layout_file;
                }
            }
            else {
                self::$main_layout = $this->findMainLayoutByControllerNamespace($main_layout_file);
            }
        }
        else {
            throw new ServiceException(500);
        }
    }

    /**
     * @param $namespace
     * @param bool|false $bad_layout
     * @return string
     *
     * convert controller namespace to layout path
     */
    private function findMainLayoutByControllerNamespace($namespace, $bad_layout = false) {
        $app_path = ServiceFactory::get('config')->getConfig('app_path');
        $map = ServiceFactory::get('config')->getConfig('namespaces');
        $main_layout_path = $app_path . '/App/Views/layouts/main_layout.html.php';//default layout path

        if ($bad_layout) {
            return $main_layout_path;
        }

        foreach($map as $prefix => $dir) {
            if(preg_match('/^' . $prefix . '/', $namespace)) {
                $path = $app_path . '/' . $dir . $prefix . '/Views/layouts/layout.html.php';
                if(file_exists($path)) {
                    $main_layout_path = $path;
                }
                break;
            }
        }
        return str_replace('/', DIRECTORY_SEPARATOR, $main_layout_path);
    }

    public static function renderMain($content, $flush = array(array())) { //render layout
        $route = Router::getInstance()->parseRoute($_SERVER['REQUEST_URI']);
        return self::render(self::$main_layout, compact('content', 'route', 'flush'), false);
    }

    public static function render($template_path, $data = array(), $wrap = true) { //render template
        extract(include('helper.php'));
        extract($data);

        ob_start();
        include($template_path);
        $content = ob_get_contents();
        ob_end_clean();
        $flush = ServiceFactory::get('session')->grabFlush();

        if($wrap) {
            $content = self::renderMain($content, $flush);
        }
        return $content;
    }
}