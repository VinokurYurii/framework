<?php

namespace Test\Controllers;

class TestController {
    /**
     * @Route(
     *     pattern=/test/another,
     *     name=another_action
     * )
     */
    public function anotherAction() {
        return 'Another Action TestController<br>';
    }

}