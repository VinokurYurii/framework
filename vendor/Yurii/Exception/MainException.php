<?php

namespace Yurii\Exception;

use Yurii\DI\Service;
use Yurii\Response\ResponseRedirect;
use Yurii\Renderer\Renderer;
use Yurii\Response\Response;

/**
 * Class MainException
 * @package Yurii\Exception
 *
 * main abstract class for exceptions
 */
abstract class MainException extends \Exception {
    protected $type = 'info'; // default type of exception
    protected $beforeSolve = false; //flag for starting beforeSolve method (default off)
    protected $redirectAddress = '/'; // default redirect address to index page

    protected function beforeSolveException() {} //by default this function is empty

    /**
     * @throws ServiceException
     *
     * solve exception depending from enter message (number or string)
     * if message is numeric get code from Response class and redirect to 500 html page
     * if string redirect to $redirectAddress
     */
    public function solveException() {
        $data = array();
        if ($this->getMessage() && is_numeric($this->getMessage())) {
            $data['code']    = $this->getMessage();
            $data['message'] = Response::getMessageByCode($this->getMessage());

            if ($this->beforeSolve) {
                $this->beforeSolveException();
            }

            $renderer = new Renderer();
            $responce = new Response($renderer::render(Service::get('config')->get500Layout(), $data), 'text/html', 202);
            $responce->send();
        }
        else if ($this->getMessage()) {
            Service::get('session')->addFlush($this->type, $this->getMessage());

            if ($this->beforeSolve) {
                $this->beforeSolveException();
            }
            echo $this->redirectAddress;

            $redirect = new ResponseRedirect($this->redirectAddress);
            $redirect->sendHeaders();
        }
        else {
            throw new ServiceException(500);
        }
    }
}