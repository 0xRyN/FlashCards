<?php
include_once 'Core/init.php';
$_user = new User();
if($_user->isLoggedIn()) {
    $tempVerify = 1;
    $_username = $_user->data()->username;
    $_name = $_user->data()->name;
    if(Cookie::exists("id$_username")) {
        $_id = Cookie::get("id$_username");
        $_card = Cookie::get("card$_username");
        $_user = Cookie::get("username$_username");
        $id = $_GET['id'];
        $card = $_GET['card'];
        
        if($_id != $id){ // si mauvais paquet
            $tempVerify = 0;
            Session::flash("gamefinished", "$_name, end this game first !");
            Redirect::to('index');
        }
    }
    if($tempVerify == 1){ //si 0 cookie ou si le paquet est le meme
        Cookie::add("id$_username", $_GET['id'], 86400);
        Cookie::add("card$_username", $_GET['card'], 86400);
        Cookie::add("username$_username", $_username, 86400);
    }
}
?>

<!DOCTYPE html>

<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="cards.css">
    <title>Games</title>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
                <li><a href="manage">Create</a></li>
                <?php
                $user = new  User();
                if($user->isLoggedIn()){
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

<body class="gamesLearn">
   <div class="card-placeholder">
    <?php
    if(!(isset($_GET['id'])) || !(isset($_GET['card']))){ //
        Redirect::to('index');//lien mauvais
    }
    $valueId = $_GET['id'];
    $valueCard = $_GET['card'];
    $temp = $valueCard;
    $k=0;
    if(is_numeric($valueId)){
        if(is_numeric($valueCard)){
                        CardGroup::getCards($valueId, $valueCard, $temp); //$valueCard sert a naviguer entre les cartes - $temp sert a afficher "test yourself"
                    }
                }
                ?>
            </div>
            <?php
            if($valueCard=='no'){
                $user = new User();
                if($user->isLoggedIn()){
                    if(Input::get('report')){
                        if(!Session::exists("report{$valueId}")){
                            Session::insert("report{$valueId}", 'yes');
                            $reps = DB::getInstance()->get('games', array('id', '=', $valueId))->getFirst()->reports;
                            DB::getInstance()->update("games", $valueId, array('reports' => $reps + 1));
                        } else {
                            //  à voir
                        }
                    }
                    $temp = $user->data()->username;
                    $valueUser = $_GET['user'];
                    if($temp != $valueUser){
                        echo " <h1> Something weird happened, the url is wrong</h1> ";
                    }else{
                        $nameOfUser = $user->data()->name;
                        echo " <h1>Can you pass the test $nameOfUser ?</h1> ";
                    }
                    
                }else{
                    echo " <h1>You are not <a href=\"login\">logged in</a> - the test will not get you any points</h1><br><h1>You can't report or like the game either.</h1> ";
                }
                CardGroup::getCards($valueId, $valueCard, $temp);
                if(Input::get("sub5")){
                    $score=0;
                    for($i=1; $i<=5; $i++){ //prend toute les reponse saisi
                        $answer = Input::get("sub$i");
                        $question = Input::get("qes$i");
                        $correctAnswer = 'none';
                        $cA = DB::getInstance()->query("SELECT cardBack, cardOrder, name, category FROM cards WHERE cardGroup = $valueId ORDER BY id DESC")->getResults();
                        foreach($cA as $k){
                            $order = $k->cardOrder;
                            if($order == $question){
                                $correctAnswer = $k->cardBack;
                                $nameOfGame = $k->name;
                                $catOfGame = $k->category;
                            }
                        }
                        //récup les valeurs
                        $tempCorrectAnswer = CardGroup::cleanString($correctAnswer);
                        $tempAnswer = CardGroup::cleanString($answer);
                        
                        if($tempCorrectAnswer == $tempAnswer){
                            $score+=20;//reponse juste
                        }
                    }
                    
                    
                    $getGameInfo = DB::getInstance()->query("SELECT played, score, level, liked FROM games WHERE id = $valueId")->getResults();
                    foreach($getGameInfo as $y){ //prend le nombre de fois que le jeu a été joué et le score total
                        $gamePlayed = $y->played;
                        $gameScore = $y->score;
                        $gameLevel = $y->level;
                        $gameLike = $y->liked;
                    }
                    if($user->isLoggedIn()){
                        if(Input::get('likeG')){ //si like alors +1 
                            if(!Session::exists("likeG{$valueId}")){
                                Session::insert("likeG{$valueId}", 'yes');
                                $gameLike++;
                            }
                        }else{
                            if(Session::exists("likeG{$valueId}") && $gameLike>0){
                                $gameLike--; //si l'utilisateur veut retirer son like
                            }
                        }
                    }
                    $gamePlayed++;
                    $gameScore = $gameScore + $score;
                    DB::getInstance()->update('games', $valueId, array("played" => $gamePlayed, "score" => $gameScore, "liked" => $gameLike));//insere les nouvelles valeurs
                    $averageOfGame = $gameScore/$gamePlayed; //moyenne sur un jeu
                    $averageOfGameCeil = ceil($gameScore/$gamePlayed); //arrondi au supérieur pour éviter les 34.666666666667
                    $congrats = 'Nice try !'; //$congrats sert a ajouter un petit message 'personnalisé'
                    if($score >= $averageOfGame){
                        $congrats = 'Well done !';
                    }
                    if($user->isLoggedIn()){
                        $userId = $user->data()->id;
                        //modifie le score de l'utilisateur
                        $userPoints = DB::getInstance()->query("SELECT userScore FROM users WHERE id = $userId")->getFirst()->userScore;
                        $userPoints += (($score/20)*$gameLevel); //1 point par bonne réponse et total multipplié par la difficulté du jeu
                        DB::getInstance()->update('users', $userId, array("userScore" => $userPoints));
                        //delete les cookies forcant l'utilisateur a finir le jeu
                        Cookie::delete("id$_username");
                        Cookie::delete("username$_username");
                        Cookie::delete("card$_username");
                    }
                    
                    //message pour avertir de la fin du jeu, du score et de la moyenne
                    Session::flash("gamefinished", "You got $score% at the game \"$nameOfGame\" ($catOfGame) while the average is $averageOfGameCeil%. $congrats");
                    Redirect::to('index');
                }
            }
            ?>

            
            <script>
        function flip(event){ //permet de retourner les cartes en cliquant
            var element = event.currentTarget;
            if (element.className === "card") {
                if(element.style.transform == "rotateY(180deg)") {
                    element.style.transform = "rotateY(0deg)";
                }
                else {
                    element.style.transform = "rotateY(180deg)";
                }
            }
        }
        function reset() {

        }
    </script>
</body>
</html>