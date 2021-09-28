<?php
require_once 'Core/init.php';
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
    <title>Login</title>
    <nav class="navbar">
        <div class="main"><a href="index">Card Game</a></div>
        <div class="links">
            <ul>
             <li><a href="register">Register</a></li>
         </ul>
     </div>
 </nav>
</head>

<body id="">
    <?php
    if(!(Input::exists())){
        Session::finalFlash();
    }
    ?>
    <div class="header">
        <div>Login<span>Page</span></div>
    </div>
    <br>
    <div class="login">
        <form method="post">
            <input type="text" placeholder="Username" name="username"><br>
            <input type="password" placeholder="Password" name="password"><br>
            <input class="button" type="submit" value="Login"><br>
            <input type="hidden" name="CSRFToken" value="<?php echo Token::generate(); ?>">
            <label class="container">Remember me
                <input type="checkbox" name="remember">
                <span class="checkmark"></span>
            </label>
            <span class="orElse">or <a href="register" >register</a></span>
        </form>
        
        <?php
        
        if(Input::exists()) {
            if(Token::check(Session::get('CSRFToken'))) {
                $validate = new Validate();
                $validate->check(
                    array(
                        'username' => array(
                            'required' => true,
                        ),

                        'password' => array(
                            'required' => true,
                        )
                    )
                )->error();
                if($validate->passed()) {
                    $user = new User();
                    $remember = Input::get('remember') === 'on' ? true : false;
                    try {
                        if($user->login(Input::get('username'), Input::get('password'), $remember)) {
                            Redirect::to("index");
                        }
                    } catch(Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        }
        ?>

    </div>

</body>
</html>
