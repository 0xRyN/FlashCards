<?php 

include_once "Core/init.php";
$user = new User();
    if($user->isLoggedIn()){//reject demande d'admin
    if($user->hasPermission("admin")) {
        $reqid = $_GET['req'];
        $user2 = new User($reqid);
        DB::getInstance()->delete('requests', array("reqid", "=", $reqid));
        $username = $user2->data()->username;
        Session::flash("adminRejSuccess", "You refused $username as an admin !");
        Redirect::to(requests);
    } else {
        Redirect::to(index);
    }
}else{
    Redirect::to(index);
}

?>
