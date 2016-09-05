<?php

namespace App\Controllers;

/**
 * Class ShopController
 * @package Shop\Controllers
 */
class AppController {
    /**
     * @Route(
     *     pattern=/sto,
     *     name=app_action,
     *     _requirements=[
     *         product= \w+,
     *          id = \d+
     *     ],
     *     _reqents=[
     *         proct= tt+,
     *          d = \g+
     *     ]
     * )
     */
    public function someAction($product) {
        return 'Some Action ShopController product: ' . $product . '<br>';
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