<?php

Class CardGroup {

    public static function cleanString($str){ //correction de string
        //supprime les accents
        $str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
        //évite les fautes a cause d'un espace en trop et les supprime
        $str = str_replace(' ', '', $str); 
        //enlève les char speciaux &#/ sauf '-' (pour les réponses négative)... évite certaines fautes 
        $str = preg_replace('/[^A-Za-z0-9-]/', '', $str);
        //met en maj pour éviter les erreur qui n'en sont pas vraiment
        $str = strtoupper($str);
        
        return $str;
    }
    
    public static function getCards($id, $ord, $t) { //Prend un paramètre et setup le nombres de cartes voulues
        $db = DB::getInstance();
            $a = 5;
            $cA = DB::getInstance()->query("SELECT * FROM cards WHERE cardGroup = $id ORDER BY id DESC")->getResults();
            foreach($cA as $key => $id){
                if($ord == 'no'){
                    self::questions($id->cardFront, $a);
                    $a--;
                }
                else if($ord == $key){
                    self::addCard($id->cardFront, $id->cardBack, $id->cardGroup, $ord, $t);
                }
            }
    }

    private static function addCard($front, $back, $idC, $card, $tem) { //Ajoute le code pour les cartes
        echo "<div class=\"scene\">
                <div class=\"card\" onclick=\"flip(event)\">
                <div class=\"card__face front\">$front</div>
                <div class=\"card__face back\">$back</div>
                </div>
                <div>";
        $k=0;
        if($card==1){
            $k=1;
            $card--;//evite de bloquer les boutons
        }else if($card>0){
            $card--;//previous, carte davant
        }
        echo "
                <input class=\"prevOrNext\" type=\"submit\" value=\"Previous\" onclick=\"location.href='games?id=$idC&card=$card';\">";
        if($card==0 && $k==0){
            $card++;//carte dapres
        }else if($card>=0 && $card<3){
            $card=$card+2;//+2 pour aller une carte en avant 
        }else{
            $card=4;
        }
        if($tem==4){
            $user = new User();
            if($user->isLoggedIn()){//bouton previous et next
                $temp = $user->data()->username;
                echo "
                    <input class=\"prevOrNext\" id=\"cNext\" type=\"submit\" value=\"Test yourself\" onclick=\"location.href='games?id=$idC&card=no&user=$temp';\">
                    </div>
                  </div>";
            }else{//accès au test
               echo "
                    <input class=\"prevOrNext\" id=\"cNext\" type=\"submit\" value=\"Test yourself\" onclick=\"location.href='games?id=$idC&card=no&user=no';\">
                    </div>
                  </div>";
            }
        }else if($tem >= 0 && $tem <= 4){
            echo "
                <input class=\"prevOrNext\" id=\"cNext\" type=\"submit\" value=\"Next\" onclick=\"location.href='games?id=$idC&card=$card';\">
                </div>
              </div>";
        }
    }
    
    private static function questions($q, $x){
        if($x==5){ //affiche le questionnaire
            echo "<form class=\"putInMiddle\" method=\"post\" action=\"\">";
        }
        $temp = $x-1;
        echo "<div class=\"answerGame\">
                <div class=\"checkPositionValid\">
                    <div id=\"cAnswer\">$q</div>
                    <input type=\"hidden\" value=\"$temp\" name=\"qes$x\">
                    <input type=\"text\" placeholder=\"ANSWER\" name=\"sub$x\" required maxlength=\"30\"> 
                    </div>
                </div>";
        if($x==1){
            $txt = '';
            $user = new User();
            if($user->isLoggedIn()){
                if(Session::exists("likeG{$_GET['id']}")){
                    $txt = 'checked'; //auto check le coeur si la session a deja like
                }
            }
            echo "<div><input name=\"submitAnswer\" type=\"submit\" value=\"SUBMIT ANSWERS\">
            <input type=\"checkbox\" id=\"likeG\" name=\"likeG\" value=\"heart\" $txt>
            <label for=\"likeG\">&#10084;</label>
            </div>
            </form>";
            echo "<form method=\"post\"><div><input name=\"report\" type=\"submit\" value=\"REPORT TO ADMINS\"></div>
            </form>";
        } 
    }
    
}