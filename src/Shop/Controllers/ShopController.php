<?php

namespace Shop\Controllers;

/**
 * Class ShopController
 * @package Shop\Controllers
 */
class ShopController {
    /**
     * @Route(
     *     pattern=/store/some/{product},
     *     name=some_action,
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
     *     pattern=/store/else,
     *     name=else,
     * )
     */
    public function elseAction() {
        return 'Else Action ShopController product<br>';
    }

    private function add() {
    }
}