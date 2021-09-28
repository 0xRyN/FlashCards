<?php
require_once 'Core/init.php';
$user = new  User();
if(!($user->isLoggedIn())){
    Session::flash('needConnect', 'You need to connect to access this page');
    Redirect::to("login");
}
$userPerm = $user->getPermission();
$userId = $user->data()->id;
if($userPerm == 'Utilisateur'){
    Session::flash('needPermAccess', 'You need to be a creator/admin to access this page');
    Redirect::to("account?action=role");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>My Games</title>
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


    <br>
    <div class="disp">
        <?php
        if(isset($_GET['id'])){ //Permet de changer les paquets de carte
            echo "<div class=\"returnSymbol\" onclick=\"location.href='mygames';\">&#8617</div>"; // n'aura aucun effet sauf pour les admin
            $id = $_GET['id'];
            
            $tempGetUser = DB::getInstance()->get('games', array("id", "=", $id))->getResults();
            foreach($tempGetUser as $k){
                $takeUid = $k->uid; //prend l'id de l'utilisateur qui a créé le paquet de carte
                $gameLevel = $k->level; // prend le level du paquet
                $gameDescription = $k->description;
            }
            if($userPerm == 'Administrateur' || $userId == $takeUid){ //vérifie que l'utilisateur est admin ou alors qu'il a bien créé ce jeu de carte

            if($takeInf = DB::getInstance()->get('cards', array("cardGroup", "=", $id))->getResults()){
                $i = 1;
                foreach($takeInf as $cards){
                    $nameFront = $cards->cardFront;
                    $nameBack = $cards->cardBack;
                    $nameCategory = $cards->category;
                    $nameName = $cards->name;

                        if($i==1){ //si premiere valeur alors le input pour le nom est créé 
                            echo "<div class=\"create\">
                            <form method=\"post\" action=\"\" class=\"addCardForm\">
                            <input type=\"text\" placeholder=\"My Card Game name\" value=\"$nameName\" name=\"name\" required maxlength=\"12\"><br>
                            <input type=\"text\" placeholder=\"Game description\" value=\"$gameDescription\" name=\"description\" required maxlength=\"120\"><br>
                            ";
                        }

                        //imprime toute les questions
                        echo " 
                        <input type=\"text\" placeholder=\"Question $i\" name=\"front$i\" value=\"$nameFront\" required maxlength=\"50\"><br>
                        <input type=\"text\" placeholder=\"Answer $i\" name=\"back$i\" value=\"$nameBack\" id=\"back\" required maxlength=\"30\"><br>";

                        if($i==5){ //si derniere valeur alors affiche la selection des categories
                            $temp1 = $temp2 = $temp3 = '';
                            if($gameLevel==1){ // sert a cocher le bon level du paquet 
                                $temp1 = 'checked';
                            }else if($gameLevel==2){
                                $temp2 = 'checked';
                            }else if($gameLevel==3){
                                $temp3 = 'checked';
                            }
                            echo "<div class=\"level\">
                            <p>Difficulty:</p> 
                            <input type=\"radio\" id=\"star3\" name=\"level\" value=\"3\" $temp3 />
                            <label for=\"star3\">&#9733;</label>
                            <input type=\"radio\" id=\"star2\" name=\"level\" value=\"2\" $temp2 />
                            <label for=\"star2\">&#9733;</label>
                            <input type=\"radio\" id=\"star1\" name=\"level\" value=\"1\" $temp1 />
                            <label for=\"star1\">&#9733;</label><br>
                            </div><br>
                            <div class=\"cat\">
                            <select name=\"category\" required>
                            <option value=\"$nameCategory\">$nameCategory</option>";
                            $a=0;
                            if($res = DB::getInstance()->get('category', array("id", ">", $a))->getResults()) {
                                foreach($res as $a) {
                                    $name = $a->catname;
                                        if($nameCategory != $name){ //n'affiche pas une deuxieme fois la catégorie utilisé
                                        echo "<option value=\"$name\">$name</option>";
                                    }
                                }
                            }
                            echo "
                            </select>
                            </div>";
                        }
                        $i++;
                    }
                    echo "<input class=\"button\" type=\"submit\" value=\"Update my card game\"><br>
                    </form>
                    <form method=\"post\" action=\"\">
                    <input type=\"submit\" name=\"delPack\" value=\"Delete\">
                    </form>
                    </div>";
                }
                if((Input::get("name")) && (Input::get("category"))){

                    $tempValid = 0;
                    for($a=1; $a<=5; $a++){
                        if(Input::get("front$a") && Input::get("back$a")){
                            $tempValid++;//verifie l'existence des inputs
                        }
                    }
                    if($tempValid>4){
                        $updName = Input::get("name");
                        $updCategory = Input::get("category");
                        $updDescription = Input::get("description");
                        $o='4';
                        for($k=1; $k<6; $k++){
                            $updFront = Input::get("front$k");
                            $updBack = Input::get("back$k");
                            DB::getInstance()->updateCard('cards', $id, $o, array("cardFront" => $updFront, "cardBack" => $updBack, "name" => $updName, "category" => $updCategory));
                            $o--;
                        }
                        DB::getInstance()->update('games', $id, array("name" => $updName, "category" => $updCategory, "description" => $updDescription));
                        Session::flash("changeSuccess", "You modified the game \"$updName\" !");
                        Redirect::to('mygames');
                    }
                }

                if(Input::get("delPack")){
                    $packName = Input::get("name");
                    DB::getInstance()->delete('games', array("id", "=", $id));
                    DB::getInstance()->delete('cards', array("cardGroup", "=", $id));
                    Session::flash("changeSuccess", "You deleted the game with success !");
                    
                    $username = $user->data()->username;
                    if(Cookie::exists("id$username")){
                        $_id = Cookie::get("id$username");
                        if($id == $_id){
                            Cookie::delete("id$username");
                            Cookie::delete("username$username");
                            Cookie::delete("card$username");
                        }
                    }
                    
                    Redirect::to('mygames');
                }
                
            }else{ //Redirect si l'utilisateur essay de changer manuellement le lien
            Session::flash("noAccessGames", "You don't have access to this game");
            Redirect::to('mygames');
        }
        }else{ //lien=mygames: afficher tous les jeux de cartes
            if($userPerm == 'Administrateur'){
                $linkTo = 'settings';
            }else{
                $linkTo = 'account';
            }
            echo "<div class=\"returnSymbol\" onclick=\"location.href='$linkTo';\">&#8617</div>";
            
            Session::finalFlash(); 
            echo "<div>Games you created</div>";
            GamesGroup::getYourGames();
        }
        ?>
    </div>

</body>
</html>
