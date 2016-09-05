<?php
return array(
    'getRoute' => function ($name, $params = array()) { //delegate to RouterService method
        return \Yurii\DI\Service::get('router')->generateRoute($name, $params);
    },
    'include' => function($controller, $method, $data = array()) {
        $controllerReflication = new \ReflectionClass($controller);
        $action = $method . 'Action';
        if ($controllerReflication->hasMethod($action)) {
            $controller = $controllerReflication->newInstance();
            $actionReflication = $controllerReflication->getMethod($action);

            if (!empty($data)) {
                $response = $actionReflication->invokeArgs($controller, $data);
            } else {
                $response = $actionReflication->invoke($controller);
            }
            if ($response instanceof \Yurii\Response\Response) {
                $response->sendBody();
            } else {
                throw new \Yurii\Exception\HttpNotFoundException('Ooops in view include');
            }
        }
    },
    'generateToken' => function() { //delegate to SecurityService method
        return \Yurii\DI\Service::get('security')->generateToken();
    }
);
