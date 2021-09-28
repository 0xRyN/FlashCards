<?php
//Cette classe contiendra les variables globales; ainsi, au lieu de $GLOBALS['mysql']['username'] (qui est dans Core/init.php), on utilisera Config::get('mysql/username') | voir DB.php
Class Config {

    public static function get($path = null) {
        if($path) {
            $config = $GLOBALS['config'];
            $string = explode('/', $path);
            foreach($string as $bit) {
                if(isset($config[$bit])) {
                    $config = $config[$bit];
                }

            }
            return $config;
        }
    }
}