<?php

namespace Yurii\Services;
/**
 * Class SessionService
 * @package Yurii\Services
 */
class SessionService {
    private static $instance;

    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
/*
 * extract returnUrl from session
 *
 * return returnUrl if exist or null
 */
    public function __get($name) {
        if ($name === 'returnUrl') {
            return isset($_SESSION['returnUrl']) ? $_SESSION['returnUrl'] : null;
        }
    }

    public function __set($name, $value) { //set returnUrl in session
        if ($name === 'returnUrl') {
            $_SESSION['returnUrl'] = $value;
        }
    }

    public function __unset($name) { //unsetset returnUrl in session
        if ($name === 'returnUrl') {
            unset($_SESSION['returnUrl']);
        }
    }

    public function startSession() {
        session_start();
    }

    public function addFlush($type, $msg) { //add to session flush messages
        if(!isset($_SESSION['flush'][$type])) {
            $_SESSION['flush'][$type] = array();
        }
        array_push($_SESSION['flush'][$type], $msg);
    }

    public function clearFlush() { // clear session flush
        $_SESSION['flush'] = array(array());
    }

    public function getFlush() { // extract and return session flush if exist or return empty array
        if(!isset($_SESSION['flush'])) {
            $_SESSION['flush'] = array(array());
        }
        return $_SESSION['flush'];
    }

    public function grabFlush() { // extract and return session flush if exist or return empty array and clear flush
        if(!isset($_SESSION['flush'])) {
            $_SESSION['flush'] = array(array());
        }
        $flush = $_SESSION['flush'];
        $this->clearFlush();
        return $flush;
    }
}