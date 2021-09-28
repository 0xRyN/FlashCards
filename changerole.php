<?php 
    //l'utilisateur selectionné va soit avoir un rang plus haut ou alors plus bas (ex: de utilisateur a creator)
include_once "Core/init.php";
$user = new User();
if($user->isLoggedIn()){
    if($user->hasPermission("admin")) {
        $userId = $_GET['user'];
        $rank = $_GET['rank'];
        $gid = $_GET['gid'];
        $user2 = new User($userId);
        if($rank == 'up'){
            if($gid == 1){
                $gid = 2;
            }else if($gid == 2 || $gid == 3){
                $gid = 3;
            }else{
                Redirect::to('index');
            }
        }else if($rank == 'down'){
            if($gid == 3){
                $gid = 2;
            }else if($gid == 2 || $gid == 1){
                $gid = 1;
            }else{
                Redirect::to('index');
            }
        }else{
            Redirect::to('index');
        }
        $user2->update(array('gid' => $gid));
        $username = $user2->data()->username;
        Session::flash("changeRole", "$username has a new role !");
        Redirect::to('manage_users');
    }else {
        Redirect::to('index');
    }
}else {
  Redirect::to('index');
}

?>