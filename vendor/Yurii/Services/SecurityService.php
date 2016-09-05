<?php

namespace Yurii\Services;

use Blog\Model\User;
use Yurii\Request\Request;

class SecurityService implements ServiceInterface {
    private static $instance;

    public static function getInstance() {
        if(empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function isAuthenticated() {
        /**
         * checked user autorization
         *
         * return boolean
         */
        return isset($_SESSION['user']->id) ? true : false;
    }

    public function clear() {
        /**
         * clear user object from session
         */
        unset($_SESSION['user']);
    }

    public function setUser (User $user) {
        /**
         * set user object in session
         */
        $_SESSION['user'] = $user;
    }

    public function getUser() {
        /**
         * return user object from session or null
         */
        return isset($_SESSION['user']) ? $_SESSION['user'] : null;
    }

    public function getSoltedPassword($password) {
        /**
         * hashed password with solt
         *
         * return array(hash of 'soltedPassword' + solt)
         */
        $solt = $this->getRandomNumbers();
        $soltedPassword = hash('sha256', $password . $solt);
        return array('soltedPassword' => $soltedPassword, 'solt' => $solt);
    }

    private function getRandomNumbers() {
        /**
         * return random 10 character long number
         */
        $r_number = '';
        for($i = 0; $i < 10; $i++) {
            $r_number .= rand(0, 9);
        }
        return $r_number;
    }

    public function isPasswordMatch($password, $user) {
        /**
         * compare output password with password from db (used solt)
         *
         * return boolean
         */
        return hash('sha256', $password . $user->solt) === $user->password;
    }

    public function generateToken() {
        /**
         * generate token and puts it in session, cookie and hidden field
         */
        $token = hash('sha256', $this->getRandomNumbers());
        $this->addToSession('token', $token);
        setcookie('token', $token);
        echo '<input type="hidden" name="token" value="' . $token . '">';
    }

    private function addToSession($name, $value) {
        /**
         * added to session named param value
         */
        $_SESSION[$name] = $value;
    }

    public function getCookie($name) {
        /*
         * get cookie by name
         *
         * return value or false
         */
        if (isset($_COOKIE[$name])) {
            return htmlspecialchars($_COOKIE[$name]);
        }
        return false;
    }

    public function grabCookie($name) {
        /**
         * get cookie value by name and clear cookie with this name
         *
         *return value or false
         */
        $value = $this->getCookie($name);
        if ($value) {
            unset($_COOKIE[$name]);
            return $value;
        }
        return false;
    }

    public function isTokenMatch() {
        /**
         * compare token in cookie, session and hiden field
         *
         * return boolean
         */
        if(!empty($_COOKIE['token']) && !empty($_POST['token'])) {
            return $this->grabCookie('token') == $_POST['token'] ? $_POST['token'] == $_SESSION['token'] : false;
        }
        return false;
    }
}