<?php


class Session //Gere la variable superglobal $_SESSION
{

    public static function get($name) {
        return $_SESSION[$name];
    }

    public static function insert($name, $value) {
        $_SESSION[$name] = $value;
        return $value;
    }

    public static function delete($name) {
        if(self::exists($name)) {
            unset($_SESSION[$name]);
        }
    }

    public static function exists($name) {
        return isset($_SESSION[$name]) ? true : false;
    }

    public static function flash($name, $msg = '') { //Affiche un message uniquement une fois.
        if(!empty($msg)) {
            self::insert($name, $msg);
            if(!isset($_SESSION['names'])) {
                $_SESSION['names'] = array();
            }
            array_push($_SESSION['names'], $name);
        } else {
            if(self::exists($name)) {
                $temp = self::get($name);
                self::delete($name);
                return $temp;
            }
        }
    }

    public static function finalFlash() { //Au lieu d'utiliser Session::flash('messageLogin') par exemple, il le fait automatiquement.
    if(self::exists('names')) {
        foreach ($_SESSION['names'] as $name) {
            echo '<span style="font-size: 1.5em; color: red; align: center;">'.Session::flash($name)."</span>";
        }
    }
}

}