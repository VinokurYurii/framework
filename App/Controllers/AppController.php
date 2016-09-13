<?php

namespace App\Controllers;

use Yurii\Controller\Controller;

/**
 * Class ShopController
 * @package Shop\Controllers
 */
class AppController extends Controller {
    /**
     * @Route(
     *     pattern=/sto/{id},
     *     name=app_action,
     *     _requirements=[
     *          id = \d+
     *     ]
     * )
     */
    public function someAction($product) {
        return $this->render('app.html');
    }

    /**
     * @Route(
     *     pattern=/store/else/belse/belse,
     *     name=else,
     * )
     */
    public function elseAction() {
        return 'Else Action ShopController product<br>';
    }

    private function add() {
    }
}