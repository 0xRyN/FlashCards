<?php 
    //l'utilisateur selectionné va devenir admin
include_once "Core/init.php";
$user = new User();
if($user->isLoggedIn()){
    if($user->hasPermission("admin")) {
        $reqid = $_GET['req'];
        $user2 = new User($reqid);
        $user2->update(array('gid' => '3'));
        DB::getInstance()->delete('requests', array("reqid", "=", $reqid));
        $username = $user2->data()->username;
        Session::flash("adminSuccess", "$username is now successfully admin !");
        Redirect::to(requests);
    }else {
        Redirect::to(index);
    }
}else {
  Redirect::to(index);
}

?>
