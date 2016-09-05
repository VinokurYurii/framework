<?php

namespace Yurii\Renderer;

use Yurii\DI\Service;
use Yurii\Exception\ServiceException;
use Yurii\Response\Response;
use Blog\Model\User;

/**
 * Class Renderer
 * @package Yurii\Renderer
 */
class Renderer {

    protected static $main_template = ''; //keep path to main template

    public function __construct($main_template_file = '') {

        if (empty(self::$main_template) && !empty($main_template_file)) {
            self::$main_template = $main_template_file;
        }
        else if (empty(self::$main_template)) {
            self::$main_template = Service::get('config')->getMainLayout();
        }
        else {
            throw new ServiceException(500);
        }
    }

    public static function renderMain($content, $flush = array(array())) { //render layout
        $route = Service::get('router')->parseRoute($_SERVER['REQUEST_URI']);
        $user = Service::get('security')->getUser();
        return self::render(self::$main_template, compact('content', 'user', 'route', 'flush'), false);
    }

    public static function render($template_path, $data = array(), $wrap = true) { //render template
        extract(include('helper.php'));
        extract($data);

        ob_start();
        include($template_path);
        $content = ob_get_contents();
        ob_end_clean();
        $flush = Service::get('session')->grabFlush();

        if($wrap) {
            $content = self::renderMain($content, $flush);
        }
        return $content;
    }
}