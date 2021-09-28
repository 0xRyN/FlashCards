<?php

Class GamesGroup {
    
    
    public static function topGroups(){ //affiche les paquets préférés
        if(!empty($res = DB::getInstance()->get('games', array("played", ">", "9"))->getResults())){ //prend played>9 pour pas que les nvx paquets soient pris en compte
            $tempHard = 100;
            $tempEasy = $tempPoints = 0;
            $tempLikeH = $tempLikeE = $tempLikeF = '';
            foreach($res as $k){
                $score = $k->score;
                $played = $k->played;
                $name = $k->name;
                $id = $k->id;
                $liked = $k->liked;
                $description = $k->description;
                $average = ($score/$played);

                if($average < $tempHard){//prend le jeu avec la moins bonne moyenne
                    $gameHard = $name;
                    $tempHard = $average;
                    $tempId1 = $id;
                    $Hdesc = $description;
                    $Hliked = $liked;
                    if($liked==0){
                        $tempLikeH = 'noLikeGame';
                    }
                }if($average > $tempEasy){ // prend le jeu avec la meilleur moyenne
                    $gameEasy = $name;
                    $tempEasy = $average;
                    $tempId2 = $id;
                    $Edesc = $description;
                    $Eliked = $liked;
                    if($liked==0){
                        $tempLikeE = 'noLikeGame';
                    }
                }if($liked > $tempPoints){ //prend le jeu le plus apprécié
                    $gameFav = $name;
                    $tempPoints = $liked;
                    $tempId3 = $id;
                    $Fdesc = $description;
                    if($liked==0){
                        $tempLikeF = 'noLikeGame';
                    }
                }
            }
            $user = new  User();
            $tempCard = 0;
            if($user->isLoggedIn()){
                $username = $user->data()->username;
                if(Cookie::exists("card$username")){ // explication plus bas avec les cartes dans les catégories
                    $uName = Cookie::get("username$username");
                    $tempCard = Cookie::get("card$username");
                    if($tempCard == 'no'){
                        $tempCard = "no&user=$uName";
                    }
                }
            }
            echo "<div class=\"card-placeholder\">
            <div class=\"cardGame colorTop\" onclick=\"location.href='games?id=$tempId2&card=$tempCard';\" style=\"margin: 0 auto;\">
            <div class=\"likeGame $tempLikeE\">$Eliked<div>&#10084;</div></div>
            <div style=\"font-size: 2rem;\">Easiest game:</div>
            <div id=\"cGame2\">$gameEasy</div>
            <div class=\"cDesc\">$Edesc</div>
            </div>
            <div class=\"cardGame colorTop\" onclick=\"location.href='games?id=$tempId3&card=$tempCard';\" style=\"margin: 0 auto;\">
            <div class=\"likeGame $tempLikeF\">$tempPoints<div>&#10084;</div></div>
            <div style=\"font-size: 2rem;\">Favorite game:</div>
            <div id=\"cGame2\">$gameFav</div>
            <div class=\"cDesc\">$Fdesc</div>
            </div>
            <div class=\"cardGame colorTop\" onclick=\"location.href='games?id=$tempId1&card=$tempCard';\" style=\"margin: 0 auto;\">
            <div class=\"likeGame $tempLikeH\">$Hliked<div>&#10084;</div></div>
            <div style=\"font-size: 2rem;\">Hardest game:</div>
            <div id=\"cGame2\">$gameHard</div>
            <div class=\"cDesc\">$Hdesc</div>
            </div>
            </div>";
        }
    }

    public static function getGroups($a) { //Prend un paramètre et setup le nombres de cartes voulues
        $db = DB::getInstance();
        if(!empty($res = $db->query("SELECT * FROM games WHERE category = ? ORDER BY liked DESC", array($a))->getResults())){ //les + likés en premier
            foreach($res as $a) {
                self::addGroup($a->name, $a->id, $a->level, $a->description, $a->liked);
            }
        }else{
            echo "<div class=\"noGames\">No games for now. <a href=\"manage\">Create one</a></div>";
        }
    }

    private static function addGroup($cardName, $id, $temp, $gDesc, $nbLike) { //Ajoute le code pour les cartes
        $tempStr = $checkNoLike = '';
        for($x=0; $x<$temp; $x++){
            $tempStr = "$tempStr&#9733;";
        }
        if($nbLike == 0){
            $checkNoLike = 'noLikeGame';
        }
        $user = new  User();
        $tempCard = 0;
        if($user->isLoggedIn()){
            $username = $user->data()->username;
            if(Cookie::exists("card$username")){ // si y a des cookies
                $uName = Cookie::get("username$username");
                $tempCard = Cookie::get("card$username"); // redirige a la bonne carte
                if($tempCard == 'no'){ //si l'utilisateur est au nv du questionnaire
                    $tempCard = "no&user=$uName";
                }
            }
        }
        echo "<div class=\"cardGame\" onclick=\"location.href='games?id=$id&card=$tempCard';\">
        <div class=\"likeGame $checkNoLike\">$nbLike<div>&#10084;</div></div>
        <div id=\"cGame\">$cardName$tempStr</div>
        <div class=\"cDesc\">$gDesc</div>
        </div>";
    }
    
    public static function getYourGames(){
        $user = new User();
        $userID = $user->data()->id;
        $userPerm = $user->getPermission();
        $tempCreat = DB::getInstance()->get('games', array("uid", "=", $userID))->getResults(); // prend les jeux que le compte a créé
        $tempAdmin = DB::getInstance()->query("SELECT * FROM games WHERE uid != ? ORDER BY reports DESC", array($userID))->getResults();// si admin, prend les jeux des autres utilisateurs par ordre du nombre de reports le + important
        
        //Parcours les cartes créé par l'utilisateur
        foreach($tempCreat as $k1){
            $cardName = $k1->name;
            $cardId = $k1->id;
            echo "<div class=\"showCardUser\" onclick=\"location.href='mygames?id=$cardId';\">
            <div>$cardName</div>
            </div>";
        }
        if($userPerm=='Administrateur'){
            echo "<div>Games created by other users</div>";
            
            // parcours toute les cartes sauf celle de l'utilisateur
            foreach($tempAdmin as $k2){
                
                //Récupère les identifiants de ceux qui ont créés des cartes
                $userSearchId = $k2->uid;
                $myId = DB::getInstance()->query("SELECT id, username FROM users WHERE id = $userSearchId")->getResults();
                $nameSearchId = 'Unknown';
                $idSearchId = 0;
                foreach($myId as $getInformation){
                    $nameSearchId = $getInformation->username;
                    $idSearchId = $getInformation->id;
                }
                
                $cardName = $k2->name;
                $cardId = $k2->id;
                $reports = $k2->reports;
                echo "<div class=\"showCardAdmin\" onclick=\"location.href='mygames?id=$cardId';\">
                <div class=\"checkPositionValid\">
                <div>$cardName ($cardId)</div>
                <div>From: $nameSearchId ($idSearchId)</div>
                <div>Report(s): $reports</div>
                </div>
                </div>";
            }
        }
    }
    
    
    
}
