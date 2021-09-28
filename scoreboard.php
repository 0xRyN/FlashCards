<?php
include_once "Core/init.php";
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
        <?php
        $user = new  User();
        if($user->isLoggedIn()){
          $userId = $user->data()->id;
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

<body id="">
  <div class="returnSymbol" onclick="location.href='index';">&#8617</div>

  <div class="disp">
    <?php
    $takeScore = DB::getInstance()->query("SELECT id, name, userScore FROM users ORDER BY userScore DESC")->getResults();
    echo "<table class=\"bigSizedTable tableOfUsers\" id=\"table\">
    <tr>
    <th onclick=\"sortTable(0)\">Place</th>
    <th onclick=\"sortTable(1)\">Name</th>
    <th onclick=\"sortTable(2)\">Score</th>
    </tr>
    ";
    $place = 1;
    $tempClass = '';
            $posUser = 'posUser1'; //afficher gold-silver-bronze
            foreach($takeScore as $k){
              $getName = $k->name;
              $getScore = $k->userScore;
              $getId = $k->id;
              if($user->isLoggedIn()){
                if($getId == $userId){
                        $tempClass = 'yourScore'; //afficher lutilisateur
                      }
                    }
                    if($getScore==null){
                      $getScore = 0;
                    }
                    echo "
                    <tr id=\"$tempClass\">
                    <td class=\"$posUser\">$place.&nbsp;</td>
                    <td>$getName&nbsp;</td>
                    <td>$getScore&nbsp;</td>
                    </tr>
                    ";
                    $tempClass='';
                    $posUser++;
                    $place++;

                  }

                  echo "</table>"
                  ?>

                </div>
                <script type="text/javascript">
                  function sortTable(n) {//sort table
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
                        if(n==1){ // manière brut pour check quelle colomne on change par alphabet 
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
                            if (dir == "asc") { //parseInt pour convertir en int
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
                      if (shouldSwitch) { //switch
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
