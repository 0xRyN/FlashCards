<?php

include_once 'Core/init.php';


$user = new User();
if($user->isLoggedIn()) {
	$user->logout();
}

Redirect::to("index");