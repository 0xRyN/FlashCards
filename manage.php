<?php
include_once 'Core/init.php';
$user = new User();
if($user->isLoggedIn()){
    $userPerm = $user->getPermission();
    if($userPerm == 'Utilisateur'){
        Session::flash("needCrea", "You need to be a Creator");
        Redirect::to("index");
    }
}else{
    Session::flash("needConnect", "You need to connect to access this page");
    Redirect::to("login");
}
?>

<!DOCTYPE html>
<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Create your own cards</title>
    <nav class="navbar">
        <div class="main"><a href="index" id="homeT">Card Game</a></div>
        <div class="links">
            <ul>
                <?php
                $user = new  User();
                if($user->isLoggedIn()){
                    $userId = $user->data()->id;
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

<body id="">

    <div class="header">
        <br>
        <div>Create<span>Page</span></div>
    </div>
    <br>
    <div class="create">

        <form method="post" action="manage" class="addCardForm">
            <input type="text" placeholder="My Card Game name" name="name" required maxlength="12"><br>
            <input type="text" placeholder="Game description" name="description" required maxlength="120"><br>
            <input type="text" placeholder="Question 1" name="front0" required maxlength="49"><span>?</span><br>
            <input type="text" placeholder="Answer 1" name="back0" id="back" required maxlength="30"><br>
            <input type="text" placeholder="Question 2" name="front1" required maxlength="49"><span>?</span><br>
            <input type="text" placeholder="Answer 2" name="back1" id="back" required maxlength="30"><br>
            <input type="text" placeholder="Question 3" name="front2" required maxlength="49"><span>?</span><br>
            <input type="text" placeholder="Answer 3" name="back2" id="back" required maxlength="30"><br>
            <input type="text" placeholder="Question 4" name="front3" required maxlength="49"><span>?</span><br>
            <input type="text" placeholder="Answer 4" name="back3" id="back" required maxlength="30"><br>
            <input type="text" placeholder="Question 5" name="front4" required maxlength="49"><span>?</span><br>
            <input type="text" placeholder="Answer 5" name="back4" id="back" required maxlength="30"><br>
            <div class="level">
                <p>Difficulty:</p> 
                <input type="radio" id="star3" name="level" value="3" />
                <label for="star3">&#9733;</label>
                <input type="radio" id="star2" name="level" value="2" />
                <label for="star2">&#9733;</label>
                <input type="radio" id="star1" name="level" value="1" checked />
                <label for="star1">&#9733;</label><br>
            </div><br>
            <div class="cat">
                <select name="category" required>
                    <option value="" hidden>Categories</option>
                    <?php
                    $a=0;
                    if($res = DB::getInstance()->get('category', array("id", ">", $a))->getResults()) {
                        foreach($res as $a) {
                            $name = $a->catname;
                            echo "<option value=\"$name\">$name</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            <input class="button" type="submit" value="Create my card game"><br>
        </form>
        
        
        <?php
        if($user->getPermission()=="Administrateur"){
            echo "<h3 id=\"textCreateNewCat\">or create a new category</h3><form method=\"post\" action=\"manage\">
            <input type=\"text\" placeholder=\"New category\" name=\"newcat\" required maxlength=\"15\"><br>
            <input class=\"button\" type=\"submit\" value=\"Create a new category\"><br>
            </form>";
        }
        
        ?>
        
    </div>

    
    
    <!-- Vérifie qu'il n'y pas de jeux du même nom et de questions similaire. Si c'est le cas le jeu est créé -->
    <?php
    $a = 0;
    $check = 0;
    for($x=0; $x<5; $x++){
        if(Input::get("front$a")){
            $frontCheck = Input::get("front$a");
            if(DB::getInstance()->checkCard('cards', array("cardFront" => $frontCheck))){
            }else{
                $check=1;
            }
            $a++;
        }
    }
    if($check==0){
        $txt='4';
        if(Input::get("name")){
            $name = Input::get("name");
            $cat = Input::get("category");
            $level = Input::get("level");
            $description = Input::get("description");
            DB::getInstance()->insertGroup('games', array("name" => $name, "category" => $cat, "uid" => $userId, "level" => $level, "description" => $description));
            $getId = DB::getInstance()->query("SELECT id FROM games WHERE name = ?", array(Input::get("name")));
            $id = $getId->getFirst()->id;  
            
        }
        for($a=0; $a<5; $a++){
            if(Input::get("front$txt")){
                $front = Input::get("front$txt");
                $front = $front . '?';
                $back = Input::get("back$txt");
                DB::getInstance()->insertCard('cards', array("cardFront" => $front, "cardBack" => $back, "cardGroup" => $id, "name" => $name, "category" => $cat, "cardOrder" => $a));
                $txt--; //Reverse pour que l'affichage des cartes se fasse dans l'ordre de création
            }
        }
        if($txt<0){
            $nameGame = Input::get('name');
            $txt='4';
            Session::flash("cardCreated", "The creation of $nameGame was a success !");
            Redirect::to('index');
        }
        
    }else{
        echo "<div class=\"errorExist\">Create cards that don't exist</div>";
    }
    
    if(Input::get("newcat")){
        $name = Input::get("newcat");
        DB::getInstance()->insertCategory('category', array("catname" => $name));
        Session::flash("catCreated", "The creation of $name was a success !");
        Redirect::to('index');
        
    }
    
    ?>

</body>
</html>
