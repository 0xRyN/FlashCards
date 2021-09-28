<?php
include_once "Core/init.php";
$user = new  User();
if($user->isLoggedIn()){
    Session::flash("alreadyLogged", "You are logged in");
    Redirect::to('index');
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Register</title>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
                <li><a href="login">Login</a></li>
            </ul>
        </div>
    </nav>
</head>

<body id="">

    <div class="header">
        <div>Register<span>Page</span></div>
    </div>
    <br>
    <div class="login">
        <form method="POST">
            <input type="text" placeholder="Name" value="<?php echo escape(Input::get('name')) ?>" name="name" required><br>
            <input type="text" placeholder="Username" value="<?php echo escape(Input::get('username')) ?>"  name="username" required><br>
            <input type="password" placeholder="Password" value=""  name="password" required><br>
            <input type="password" placeholder="Confirmation" value=""  name="passwordConf" required><br>
            <input type="hidden" name="CSRFToken" value="<?php echo Token::generate(); ?>">
            <input class="button" type="submit" value="Register"><br>
            <span class="orElse">or <a href="login" >login</a></span>
        </form>
        <?php
        if(Input::exists()) {
            if(Token::check(Session::get('CSRFToken'))) {
                $validate = new Validate();
                $validate->check(
                    array(
                        'username' => array(
                            'required' => true,
                            'min' => 2,
                            'max' => 20,
                            'unique' => 'users'
                        ),

                        'password' => array(
                            'required' => true,
                            'min' => 6,
                            'max' => 64
                        ),

                        'passwordConf' => array(
                            'required' => true,
                            'matches' => 'password'
                        ),

                        'name' => array(
                            'required' => true,
                            'min' => 4,
                            'max' => 40
                        )
                    )
                )->error();
                if($validate->passed()) {
                    $user = new User();
                    $salt = Hash::salt(16);
                    try {
                        $user->add(array(
                            'username' => Input::get('username'),
                            'password' => Hash::make(Input::get('password'), $salt),
                            'salt' => $salt,
                            'name' => Input::get('name')
                        )
                    );
                        Redirect::to("login");
                        Session::flash("success", "Vous vous êtes enregistré, " . Input::get("name") . ", entrez vos identifiants !");
                    } catch(Exception $e) {
                        die($e->getMessage());
                    }
                }
            }
        }
        ?>
    </div>

</body>
</html>
