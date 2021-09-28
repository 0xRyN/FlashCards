<?php
include_once "Core/init.php";
if(!Session::exists('session/session_name')) {
    $user = new User();
    if($user->isLoggedIn()){
        $userPerm = $user->getPermission();
        if($userPerm == 'Rédacteur'){
            Redirect::to("mygames");
        }else if($userPerm == 'Utilisateur'){
            Session::flash('needPermAccess', 'You need to be a creator/admin to access this page');
            Redirect::to("account?action=role");
        }
    }else{
        Session::flash('needConnect', 'You need to connect to access this page');
        Redirect::to("login");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Settings</title>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
                <li><a href="account">My account</a></li>
                <li><a href="manage">Create</a></li>
                <li><a id="logoutH" href="logout">Logout</a></li>
            </ul>
        </div>
    </nav>
</head>

<body id="">
    <div class="header">
        <div>Settings<span>Page</span></div>
    </div>

    <div class="login">

        <div class="adminBox" onclick="location.href='mygames';">My Games</div>
        <div class="adminBox" onclick="location.href='categories';">Categories</div>
        <div class="adminBox" onclick="location.href='manage_users';">Users</div>
        <div class="adminBox" onclick="location.href='requests';">Requests</div>
        
        
    </div>

</body>
</html>
