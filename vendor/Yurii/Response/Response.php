<?php

namespace Yurii\Response;
/**
 * Class Response
 * @package Yurii\Response
 */

class Response {
    protected $headers = array();

    public $code, $content, $type;

    /**
     * @codes array collect most useless codes
     */
    private static $codes = array(200 => 'Ok',
                                202 => 'No Content',
                                303 => 'See Other',
                                400 => 'Bad Request',
                                401 => 'Unauthorized',
                                403 => 'Forbidden',
                                404 => 'Not found',
                                500 => 'Internal Server Error',
                                501 => 'Not implemented'
                                );

    public function __construct($content = '', $type = 'text/html', $code = 200) {
        $this->code = $code;
        $this->content = $content;
        $this->type = $type;
        $this->setHeader('Content-Type', $this->type);
    }

    public static function getMessageByCode($code) {
        return self::$codes[$code];
    }

    public function send() {
        $this->sendHeaders();
        $this->sendBody();
    }

    public function setCode($code) {
        $this->code = $code;
    }

    public function setHeader($name, $value) {// add header
        $this->headers[$name] = $value;
    }

    public function sendHeaders() { //send headers
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $this->code . ' ' . self::$codes[$this->code]);

        foreach($this->headers as $key => $value) {
            $this->sendHeader($key, $value);
        }
    }

    public function sendHeader($headerName, $headerValue) { // send single header
        header(sprintf("%s: %s", $headerName, $headerValue));
    }

    public function sendBody() {
        echo $this->content;
    }
}