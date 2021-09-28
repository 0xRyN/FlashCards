<?php
include_once "Core/init.php";
if(!Session::exists('session/session_name')) {
    $user = new User();
    if($user->isLoggedIn()){
        $userPerm = $user->getPermission();
        if($userPerm == 'Rédacteur'){
            Redirect::to("mygames");
        }else if($userPerm == 'Utilisateur'){
            Session::flash('needPermAccess', 'You need to be an admin to access this page');
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
    <div class="header2">
        <div>Categories<span>Page</span></div>
    </div>
    <div class="returnSymbol" onclick="location.href='settings';">&#8617</div>

    <div class="login">
        <?php
        $a=0;
        $res = DB::getInstance()->get('category', array("id", ">", $a))->getResults();
        if(!(isset($_GET['del']))){
            echo "<span class=\"delCatUp\">";
            Session::finalFlash();
            echo "</span>";
        }
        else if(isset($_GET['del'])){
            $nameCat = '';
            foreach($res as $a){
                $nameCat = $a->catname;
            }
            $deleteId = $_GET['del'];
            DB::getInstance()->delete('category', array("id", "=", $deleteId));
            Session::flash("delCatSuccess", "$nameCat($deleteId) was deleted");
            Redirect::to('categories');
        }
        echo "<table class=\"tableCat\">
        <tr>
        <th>id</th>
        <th>name</th>
        <th>delete</th>
        </tr>";
        foreach($res as $a) {
            $name = $a->catname;
            $id = $a->id;
            echo "<tr>
            <td>$id</td>
            <td>$name</td>
            <td id=\"delCat\" onclick=\"location.href='categories?del=$id';\">&#9746;</td>
            </tr>";
        }
        echo "</table>";
        
        ?>

        
    </div>

</body>
</html>
