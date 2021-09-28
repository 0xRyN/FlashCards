<?php


class Cookie { //Meme chose que la classe session mais utilise les cookies a long terme

    public static function add($name, $value, $expiry) {
        if(setcookie($name, $value, time() + $expiry, '/')) {
            return true;
        }
        return false;
    }

    public static function get($name) {
        return $_COOKIE[$name];
    }

    public static function exists($name) {
        return isset($_COOKIE[$name]) ? true : false;
    }

    public static function delete($name) {
        self::add($name, '', time() - 1);
    }
    
    
}