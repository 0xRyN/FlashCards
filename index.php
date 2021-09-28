<?php
require_once 'Core/init.php';
$user = new  User();
if($user->isLoggedIn()){
    $username = $user->data()->username;
    if(Cookie::exists("id$username")) {
        $_id = Cookie::get("id$username");
        $_card = Cookie::get("card$username");
        $_user = Cookie::get("username$username");
        if($_user == $username){
            $getGameName = DB::getInstance()->query("SELECT name FROM games WHERE id = ?", array($_id))->getFirst()->name;
            Session::flash("finishGame", "Would you like to finish \"$getGameName\"?
            <button class=\"finishCurrentGame\" name=\"yesFinish\" type=\"button\" onclick=\"location.href='games?id={$_id}&card={$_card}&user={$_user}';\">Yes</button>
            <button class=\"finishCurrentGame\" name=\"noFinish\" type=\"button\" onclick=\"location.href='cookie_del';\">No</button>
            ");
        }
    }
}
?>

<html>

<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="cards.css">
    <title>Card Game</title>
    <p id=""></p>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
                <li><a href="scoreboard">Scoreboard</a></li>
                <?php
                $user = new  User();
                if($user->isLoggedIn()){
                    echo "<li><a href=\"manage\">Create</a></li>";
                    echo "<li><a href=\"account\">My account</a></li>";
                    echo "<li><a id=\"logoutH\" href=\"logout\">Logout</a></li>";
                }else {
                    echo "<li><a href=\"login\">Login</a></li>";
                    echo "<li><a href=\"register\">Register</a></li>";
                }
                ?>
            </ul>
        </div>
    </nav>
</head>
<body class="">
    <div class="ihold-messages"> 
        <?php
        $user = new User();
        Session::finalFlash();
        if($user->isLoggedIn()) {
            if($user->getPermission() == 'Administrateur'){
                $role = 'Admin';
            }else if($user->getPermission() == 'Rédacteur'){
                $role = 'Creator';
            }else{
                $role = 'User';
            }
            echo "<br><span>Welcome, " .  $user->data()->name . ' (' . $role . ')</span>';
        } else {
            echo '<br><span>You are not <a href="login">logged in</a>.</span>';
        }
        ?>
    </div>
    <div class="card-placeholder">
    </div>
    
    <div class="indexCard">
        
        <?php
        GamesGroup::topGroups();
        CategoryGroup::getCategory();
        ?>
        
        
    </div>

</body>


</html>