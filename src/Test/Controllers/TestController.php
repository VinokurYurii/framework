<?php

namespace Test\Controllers;

use Yurii\Controller\Controller;

class TestController extends Controller {
    /**
     * @Route(
     *     pattern=/test,
     *     name=another_action
     * )
     */
    public function anotherAction() {
        return $this->render('app.html', array(
            'src' => array('src' => 'App', 'controller' => 'App')));
    }

}