<?php
namespace Yurii;

use Yurii\Exception\AuthRequredException;
use Yurii\Exception\MainException;
use Yurii\Exception\SecurityException;
use Yurii\Exception\ServiceException;
use Yurii\Response\JsonResponse;
use Yurii\Response\ResponseRedirect;
use Yurii\Exception\HttpNotFoundException;
use Yurii\Response\Response;
use Yurii\Services\ServiceFactory;
use Yurii\Request\Request;
use Yurii\Router\Router;
use App\Models\User;

class Application {

    /**
     * @static config
     *
     * keep all application config
     */

    public static $config;
    public static $router;

    /**
     * Application constructor.
     * @param $config
     */

    function __construct($config) {
        self::$config = $config;

        ServiceFactory::get('session')->startSession();
        ServiceFactory::get('config')->addConfig(self::$config);
        ServiceFactory::get('config')->setConfig('app_path', realpath(__DIR__ . '/../../'));//adding application path to config
        self::$router = Router::getInstance();
    }

    /**
     * @param $controller
     * @param $method
     * @param array $data
     * @throws HttpNotFoundException
     *
     * handle data and run needles metdod in needle controller
     */
    public static function runControllerMethod ($controller, $method, $data = array()) {
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

            if ($response instanceof ResponseRedirect) {
                $response->sendHeaders();
            }
            else if ($response instanceof JsonResponse) {
                $response->send();
            }
            else if ($response instanceof Response) {
                $response->send();
            }
            else {
                echo '<pre>';
                var_dump($response);
                echo 'throw new HttpNotFoundException(501) ' . $controllerReflication->getNamespaceName(); die;
                throw new HttpNotFoundException(501);
            }
        }
        else {
            throw new ServiceException(501);
        }
    }

    /**
     * @throws Exception\ServiceException, Exception\AuthRequredException
     *
     * run application
     */
    public function run() {
        /**
         * get route depending on REQUEST_URI
         */

        $route = self::$router->parseRoute(htmlspecialchars($_SERVER['REQUEST_URI']));
        try {
            if (!empty($route)) {
                /**
                 * check rights for this action
                 */
                if (!empty($route['security'])) {
                    /**
                     * get current user
                     */
                    $user = ServiceFactory::get('security')->getUser();
                    if (is_null($user)) {
                        throw new AuthRequredException('If you wont do this action please login or signin.');
                    }
                    //if (!in_array($user->role, $route['security'])) {
                    if (!ServiceFactory::get('security')->haveRight($user->role, $route['security'][0])) {
                        throw new SecurityException('You have not right for this action.');
                    }
                }

                self::runControllerMethod($route['controller'], $route['action'],
                    isset($route['params']) ? $route['params'] : '');
            } else {
                die('throw new HttpNotFoundException');
                throw new HttpNotFoundException('Route not found');
            }
        } catch (MainException $e) {
            $e->solveException();
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}






















