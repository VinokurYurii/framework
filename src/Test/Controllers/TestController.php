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
        return $this->render('Another Action TestController<br>');
    }

}