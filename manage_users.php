<?php
include_once "Core/init.php";
if(!Session::exists('session/session_name')) {
    $user = new User();
    if($user->isLoggedIn()){
        $userPerm = $user->getPermission();
        if($userPerm != 'Administrateur'){
            Session::flash("needAdm", "You need to be an admin to access this page");
            Redirect::to("account?action=role");
        }
    }else{
        Session::flash("needConnect", "You need to connect to access this page");
        Redirect::to("login");
    }
    
}
?>
<!DOCTYPE html>
<html>
<head>
    <link href="style2.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <title>Users</title>
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
    <div class="returnSymbol" onclick="location.href='settings';">&#8617</div>

    <div class="disp">
        <?php
        Session::finalFlash();
        $userId = $user->data()->id;
        $takeUsers = DB::getInstance()->query("SELECT id, username, name, joined, gid FROM users ORDER BY id ASC")->getResults();
        echo "<table class=\"tableOfUsers\" id=\"table\">
        <tr>
        <th onclick=\"sortTable(0)\">id</th>
        <th onclick=\"sortTable(1)\">username</th>
        <th onclick=\"sortTable(2)\">name</th>
        <th onclick=\"sortTable(3)\">role</th>
        <th onclick=\"sortTable(4)\">joined</th>
        <th>Rank Up&nbsp;</th>
        <th>R. Down&nbsp;</th>
        <th>Delete</th>
        </tr>
        ";
        foreach($takeUsers as $k){
            $getId = $k->id; 
            $getUsername = $k->username;
            $getName = $k->name;
            $getJoined = $k->joined;
            $getGid = $k->gid;
            $getRole = $k->gid;
            if($getGid==2){
                $getGid = 'Creator';
            }else if($getGid==3){
                $getGid = 'Admin';
            }else{
                $getGid = 'User';
            }
            echo "
            <tr>
            <td>$getId&nbsp;</td>
            <td>$getUsername&nbsp;</td>
            <td>$getName&nbsp;</td>
            <td>$getGid&nbsp;</td>
            <td>$getJoined</td>
            <td id=\"promoteUp\" onclick=\"location.href='changerole?rank=up&user=$getId&gid=$getRole';\">&#10224;</td>
            <td id=\"promoteDown\" onclick=\"location.href='changerole?rank=down&user=$getId&gid=$getRole';\">&#10225;</td>
            <td id=\"delUserAcc\" onclick=\"confDel('$getUsername', '$getId')\">Del</td>
            </tr>
            ";
        }
        echo "</table>"
        ?>

    </div>
    <script type="text/javascript">
        function confDel(a, b){ // verification pour pas se tromper au moment du delete
            if (confirm("You're about to delete the account of " + a + " (" + b + "). Are you sure?")) {
                window.location.href = "del_user?user=" + b;
              } else {
              }
        }            
        
        function sortTable(n) { 
            var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
            table = document.getElementById("table")
            switching = true;
            dir = "asc";
            while (switching) {
              switching = false;
              rows = table.rows;
              for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if(n >= 1 && n <= 4){
                    if (dir == "asc") {
                      if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch= true;
                        break;
                      }
                    } else if (dir == "desc") {
                      if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                      }
                    }
                }else{
                    if (dir == "asc") {
                      if (parseInt(x.innerHTML) > parseInt(y.innerHTML)) {
                        shouldSwitch= true;
                        break;
                      }
                    }else if(dir == "desc"){
                      if (parseInt(x.innerHTML) < parseInt(y.innerHTML)){
                        shouldSwitch = true;
                        break;
                      }
                    }
                }
              }
              if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount ++;      
              } else {
                if (switchcount == 0 && dir == "asc") {
                  dir = "desc";
                  switching = true;
                }
              }
            }
          }
        </script>




</body>
</html>
