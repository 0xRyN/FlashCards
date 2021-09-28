<?php


class Token { //Token pour éviter les requêtes possiblement dangereuses (supprimer compte par exemple) d'un autre site

public static function generate() {
   return Session::insert(Config::get("session/token_name"), md5(uniqid()));
}

public static function check($token) {
    $tokenName = Config::get("session/token_name");
    if(Session::get($tokenName) == $token) {
        Session::delete($tokenName);
        return true;
    }
    return false;
}
}