<?php
include_once "Core/init.php";
if(!Session::exists('session/session_name')) {
    $user = new  User();
    if(!($user->isLoggedIn())){
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
    <title>My account</title>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
                <?php
                $userPerm = $user->getPermission();
                if($userPerm=='Administrateur' || $userPerm=='Rédacteur'){
                    echo "<li><a href=\"settings\">Manage</a></li>";
                }
                ?>
                <li><a href="manage">Create</a></li>
                <li><a id="logoutH" href="logout">Logout</a></li>
            </ul>
        </div>
    </nav>
</head>

<body id="">
    <?php
    $name = $user->data()->name;
    $username = $user->data()->username;
        //permet l'affichage en anglais
    if($userPerm=='Utilisateur'){
        $role = 'User';
    }else if($userPerm=='Rédacteur'){
        $role = 'Creator';
    }else if($userPerm=='Administrateur'){
        $role = 'Admin';
    }
    $date = $user->data()->date;
    $id = $user->data()->id;
    $valueAction  = 0;
    if(isset($_GET['action'])){
        $valueAction = $_GET['action'];
    }
    ?>

    <div class="header2">
        <div><?php if($valueAction=='0'){echo "Account";}else{echo "Update";}?><span>Page</span></div> <!-- Le 'titre' de la page change-->
    </div>
    <br>
    <?php
    if($valueAction != '0'){//retour en arriere
        echo "<div class=\"returnSymbol\" onclick=\"location.href='account';\">&#8617</div>";
    }
    ?>
    <div class="login">
        <?php
        if($valueAction == '0'){//si il y 0 param alors fait laffichage des infos
            echo "<div class=\"changePage\">
            <div class=\"boxAcc\" onclick=\"location.href='account?action=name';\">Name<div class=\"infoAcc\">$name</div></div>
            <span></span>
            <div class=\"boxAcc\" onclick=\"location.href='account?action=username';\">Username<div class=\"infoAcc\">$username</div></div>
            <span></span>
            <div class=\"boxAcc\" onclick=\"location.href='account?action=password';\">Password<div class=\"infoAcc\">*********</div></div>
            <span></span>
            <div class=\"boxAcc\" onclick=\"location.href='account?action=role';\">Role<div class=\"infoAcc\">$role</div></div>
            <span></span>
            <div class=\"boxAcc\">Member since<div class=\"infoAcc\">$date</div> </div>
            <button class=\"butAcc\" type=\"button\" onclick=\"myDelete()\">Delete</button>";
            echo "</div>";
        }else if($valueAction == 'name'){ //affiche les boutons pour changer le nom
            echo "<div class=\"updateAcc\">Edit your name
            <form method=\"post\" action=\"account\">
            <input type=\"text\" name=\"changeName\" placeholder=\"Name\" value=\"$name\" required minlength=\"4\" maxlength=\"40\"><br>
            <input type=\"submit\" value=\"Update\">
            </form>
            </div>";
        }else if($valueAction == 'username'){ // changer username
            echo "<div class=\"updateAcc\">Edit your username
            <form method=\"post\" action=\"\">
            <input type=\"text\" name=\"changeUsername\" placeholder=\"Username\" value=\"$username\" required minlength=\"2\" maxlength=\"20\"><br>
            <input type=\"submit\" value=\"Update\">
            </form>
            </div>";
        }else if($valueAction == 'password'){ //edit le mot de passe
            echo "<div class=\"updateAcc\">Edit your password
            <form method=\"POST\">
            <input type=\"password\" placeholder=\"Previous password\" name=\"oldPass\"><br>
            <input type=\"password\" placeholder=\"New password\" value=\"\" name=\"password\"><br>
            <input type=\"password\" placeholder=\"New password confirmation\" value=\"\"  name=\"passwordConf\"><br>
            <input type=\"hidden\" name=\"CSRFToken\" value=\"";
            escape(Token::generate());
            echo "\">
            <input class=\"button\" type=\"submit\" value=\"Update\">
            </form>
            </div>";
        }else if($valueAction == 'role'){//change le role
            $ka = '';
            $kr = '';
            $ku = '';
            // Va mettre en évidence le role de l'utilisateur
            if($role=='Admin'){
                $ka = "takeUserRole";
            }else if($role=='Creator'){
                $kr = "takeUserRole";
            }else if($role=='User'){
                $ku = "takeUserRole";
            }
            //submit pour changer de role
            echo "<div class=\"updateAcc2\">Edit your role
            <div class=\"boxRole userRole\">
            <form method=\"post\" action=\"account\">
            <input class=\"$ku\" type=\"submit\" name=\"roleU\" value=\"User\"></form></div>
            <div class=\"boxRole creatRole\">
            <form method=\"post\" action=\"account\">
            <input class=\"$kr\" type=\"submit\" name=\"roleC\" value=\"Creator\"></form></div>
            <div class=\"boxRole adminRole\">
            <form method=\"post\" action=\"\">
            <input class=\"$ka\" type=\"submit\" name=\"roleA\" value=\"Admin\"></form></div>
            <div class=\"boxRole infRole ir1\"><ul>
            <li>Create/Manage Cards</li>
            </ul></div>
            <div class=\"boxRole infRole ir2\"><ul>
            <li>Create/Manage Cards</li>
            <li>Create/Manage Categories</li>
            <li>Manage Users</li>
            <li></li>
            </ul></div>
            </div>";
        }else if($valueAction == 'delete'){//(pas utile pour le moment)
            
        }else{
            echo "ERROR";
        }
        ?>
        <?php
        if(isset($_POST["roleU"])){ //devenir user
            $user->update(array('gid' => '1'));
            Redirect::to('account');
        }if(isset($_POST["roleC"])){//devenir creator
            $user->update(array('gid' => '2'));
            Redirect::to("account");
        }if(isset($_POST["roleA"])){//devenir admin
            $uid = $user->data()->id;
            $uname = $user->data()->username;
            if(DB::getInstance()->get('requests', array("reqid", "=", $uid))->getCount() == 0) {
                DB::getInstance()->insert('requests', array("reqid" => $uid, "username" => $uname)); //envoie une requete aux admins
                Session::flash("reqAdmin", "Your request was sent successfully");
            } else {
                Session::flash("reqFail", "You already have a request inbound");
            }
            Redirect::to("index");
        }if(isset($_POST["changeName"])){ // Change account name
            $user->update(array('name' => $_POST["changeName"]));
            Redirect::to("account");
        }if(isset($_POST["changeUsername"])){ // change account username (a unique one)
            $k=0;
            $changeUsername = $_POST["changeUsername"];
            $checkUsername = DB::getInstance()->query("SELECT username FROM users WHERE username = ?", array(Input::get("changeUsername")))->getFirst()->username;
            
            if($checkUsername == $changeUsername){ //check si il est unique
                $k=1;
            }
            if($k == 0){
                $user->update(array('username' => $_POST["changeUsername"]));
                Redirect::to("account");
            }else{
                echo "<div class=\"errorExist\">Choose a unique username</div>";
            }
        }if(isset($_POST["oldPass"])){ // changer le mdp
            if(Input::exists()) {
                if(Token::check(Session::get('CSRFToken'))) {
                    $validate = new Validate();
                    $validate->check(
                        array(
                            'oldPass' => array(
                                'required' => true,
                            ),

                            'password' => array(
                                'required' => true,
                                'min' => 6
                            ),

                            'passwordConf' => array(
                                'required' => true,
                                'matches' =>'password'
                            )

                        )
                    )->error();
                    if($validate->passed()) {
                        $user = new User();
                        $pass = $user->data()->password;
                        $salt = $user->data()->salt;
                        try {
                            if(Hash::make(Input::get('oldPass'), $salt) == $pass) {
                                $newSalt = Hash::salt(16);
                                $newPass = Hash::make(Input::get('password'), $newSalt);
                                $user->update(array('password' => $newPass, 'salt' => $newSalt));
                                Session::flash('updatedPass', 'Password changed with sucess !');
                                Redirect::to('account');
                            } else {
                                throw new Exception("Mot de passe erroné !");
                            }
                        } catch(Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                }
            }
        }
        ?>
        
        <script>
            function myDelete(){
                if(confirm("You're about to definitely delete your account.")){
                    window.location.href = "del_acc";
                }
            }
        </script>

    </div>

</body>
</html>
