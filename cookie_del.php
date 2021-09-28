<?php 
    //les cookies sont supprimés
include_once "Core/init.php";
$user = new User();
if($user->isLoggedIn()){
    $username = $user->data()->username;
    $name = $user->data()->name;
    if(Cookie::exists("id$username") && Cookie::exists("card$username") && Cookie::exists("username$username")) {
        Cookie::delete("id$username");
        Cookie::delete("username$username");
        Cookie::delete("card$username");
        Session::flash("adminSuccess", "$name, you can play other games !");
        Redirect::to('index');
    }else {
        Redirect::to('index');
    }
}else {
  Redirect::to('index');
}

?>
