<?php
require_once 'Core/init.php';
$user = new  User();
if(!($user->isLoggedIn())){
    Session::flash('needConnect', 'You need to connect to access this page');
    Redirect::to("login");
}
$userPerm = $user->getPermission();
$userId = $user->data()->id;
if($userPerm != 'Administrateur'){
    Session::flash('needPAdminAccess', "You need to be an admin to access this page");
    Redirect::to("account?action=role");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Login</title>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
                <li><a href="manage">Create</a></li>
                <?php
                if($user->isLoggedIn()){
                    echo "<li><a href=\"account\">My account</a></li>";
                    echo "<li><a id=\"logoutH\" href=\"logout\">Logout</a></li>";
                }else {
                    echo "<li><a href=\"register\">Register</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
</head>

<body id="">
    <!-- Symbole html: flèche, présant dans plusieurs pages du site -->
    <div class="returnSymbol" onclick="location.href='settings';">&#8617</div>
    <div class="disp">

        <?php 
        $reqs = DB::getInstance()->query("SELECT * FROM requests")->getResults();
        if(!empty($reqs)) {
            $i = 1;
            echo "
            <table class=\"tabReq\">
            <tr class=\"tabHead\">
            <th>Request &nbsp;</th>
            <th>Id &nbsp;</th>
            <th>Username &nbsp;</th>
            <th>Answer</th>
            </tr>
            <span class=\"tabSpan\"></span>
            ";
            foreach($reqs as $req) {
                $reqid = $req->reqid;
                echo "
                <tr>
                <td>$i.</td>
                <td>$reqid &nbsp;</td>
                <td>$req->username &nbsp;</td>
                <td><span class=\"accept\" onclick=\"location.href='accept?req=$reqid';\">&#9745;</span>
                <span class=\"refuse\" onclick=\"location.href='reject?req=$reqid';\">&#9746;</span></td>
                </tr>
                ";
                $i++;
            }
            echo "</table>";
        } else {
            echo "No requests for now !";
        }
        echo "<br>";
        Session::finalFlash();
        ?>

    </div>

</body>
</html>
