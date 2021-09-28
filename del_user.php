<?php 

include_once "Core/init.php";
$user = new User();
    if($user->isLoggedIn()){ // delete les autres utilisateur
        if($user->hasPermission("admin")) {
            $userId = $_GET['user'];
            $user2 = new User($userId);
            $username = $user2->data()->username;
            DB::getInstance()->delete('users', array("id", "=", $userId));
            Session::flash("delAccSuccess", "You deleted $username's account !");
            Redirect::to(manage_users);
        } else {
            Redirect::to(index);
        }
    }else{
        Redirect::to(index);
    }

    ?>
