<?php

session_start();

$GLOBALS['config'] = array( //Toutes les variables globales nécéessaires

    'mysql' => array(
        'host' => '127.0.0.1',
        'username' => 'root',
        'password' => '',
        'name' => 'db'
    ),

    'remember' => array(
        'cookie_name' => 'cookie',
        'cookie_expiry' => 86400
    ),

    'session' => array(
        'session_name' => 'user',
        'token_name' => 'CSRFToken'
    )

);

spl_autoload_register(function ($class) { //Charge toutes les classes automatiquement
    require_once 'Classes/' . $class . '.php';
});

require_once 'Functions/sanitize.php';


//CHECKER LES COOKIES UTILISATEURS -> SI ACCEPTEES, CONNEXION AUTO

if(Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
    $cookie_name = Config::get('remember/cookie_name');
    $cookie = Cookie::get($cookie_name);
    $check = DB::getInstance()->get('users_session', array('hash', '=', '$cookie'));
    if ($check->getCount() == 1) {
        $user = new User($check->getResults->user_id);
        $user->cookieLogin();
    }
}