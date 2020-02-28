<?php echo "<!doctype html>"; ?>

<head>
    <title>Egzamin zawodowy</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../3.css">
    <script defer src="../fontawesome-all.js"></script>
    <script src="../jquery-3.3.1.min.js"></script>
    <script src="script.min.js"></script>
</head>

<body>

    <div class="top">
        <?php
            require '../php_db/db_connect.php'; 
            if($_GET["info"]!=null){
                echo "<div class=\"info\"><i class=\"fas fa-exclamation-triangle\"></i> ";
                echo $_GET["info"];
                echo "</div>";
            }
        
            $classselect="";
            $sql = "SELECT * FROM users GROUP BY class"; 
            $result = $connect->query($sql); 
            if($result->num_rows > 0) { 
                while($row = $result->fetch_assoc()) {

                    $classselect.="<option ".($_GET['class']==$row['class']?"selected":"")." value=\"".$row['class']."\">".$row['class']."</option>"; 
                } 
            }
        
            ?>


            <?php if($_GET['class'] == null){ ?>
            <h1>Panel administracyjny</h1>
            <div class="q">
                <h2>Wybierz klasę:</h2>
                <ul>
                    <?php 
                $sql = "SELECT * FROM users GROUP BY class ORDER BY class ASC "; 
                $result = $connect->query($sql); 
                if($result->num_rows > 0) { 
                    while($row = $result->fetch_assoc()) {

                        echo "<li>";
                        echo "<a class=\"labellike\" href=\"?class=".$row['class']."\">".$row['class']."</a>"; 
                        echo "</li>"; 
                    } 
                }
                ?>

                </ul>
            </div>
            <?php }else{ ?>
            <h1>
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i>
                    Klasa:
                    <?php echo $_GET['class'] ?>
                </a>
            </h1>
            <div class="q">
                <h2><input type="checkbox" id="selectAll" title="Zaznaczy wszystko"> Wybierz ucznia:</h2>
                <input type="hidden" name="class" value="<?php echo $_GET['class'] ?>">
                <ul>
                    <?php 
                        $sql = "SELECT * FROM users WHERE class = \"".$_GET['class']."\""; 
                        $result = $connect->query($sql); 
                         if($result->num_rows > 0) { 
                            while($row = $result->fetch_assoc()) {

                                $canSolveBin=str_pad(decbin($row['canSolve']), 100, "0", STR_PAD_LEFT);
                                $canSolveIconic=($canSolveBin[99]?"<i class=\"fas fa-microchip\"></i> ":"<i class=\"faded fas fa-microchip\"></i> ").($canSolveBin[98]?"<i class=\"fas fa-sitemap\"></i> ":"<i class=\"faded fas fa-sitemap\"></i> ").($canSolveBin[97]?"<i class=\"fas fa-code\"></i> ":"<i class=\"faded fas fa-code\"></i> ");

                                $displayName="[".$row['userCode']."]: ".$row['Name']." ".$row['LastName'];

                                echo "<li><label><input type=\"checkbox\" id=\"s-".$row['userCode']."\" name=\"s-".$row['userCode']."\" class=\"userselector\"> ".$canSolveIconic." ".$displayName."</label></li>"; 
                            } 
                        }
                        ?>

                </ul>
            </div>


            <form class="q forSelectedForm" method="POST" action="../php_db/db_adm_update_users.php">
                <input type="hidden" name="where" id="where">
                <input type="hidden" name="className" value="<?php echo $_GET['class'] ?>">
                <h2 class="forSelected">Dla zaznaczonych:</h2>
                <ul>
                    <li>
                        <input id="chmod" name="action" value="chmod" type="radio">
                        <label for="chmod">Zmień uprawnienia</label>
                        <label class="auxilia">na: 
                            <select name="newchmod">
                                <option value="0">Brak uprawnień</option>
                                <option value="1">E.12</option>
                                <option value="2">E.13</option>
                                <option value="4">E.14</option>
                                <option value="3">E.12 oraz E.13</option>
                                <option value="5">E.12 oraz E.14</option>
                                <option value="6">E.13 oraz E.14</option>
                                <option value="7">E.12 oraz E.13 oraz E.14</option>
                            </select>
                        </label>
                    </li>
                    <li>
                        <input id="reclass" name="action" value="reclass" type="radio">
                        <label for="reclass">Przepisz do klasy</label>
                        <label class="auxilia">na: 
                            <select name="newreclass">
                                <?php echo $classselect; ?>
                            </select>
                        </label>
                    </li>
                    <li>
                        <input id="inclass" name="action" value="inclass" type="radio">
                        <label for="inclass">Stwórz nową klasę i przepisz do niej</label>
                        <label class="auxilia">na: <input name="newinclass"></label>
                    </li>
                    <li>
                        <input id="remove" name="action" value="remove" type="radio">
                        <label for="remove">Usuń uczniów</label>
                        <label class="auxilia"><input name="newremove" type="checkbox"><b style="color: red">TAK, chcę usunąć wyżej zaznaczonych uczniów oraz ich historię z bazy</b></label>
                    </li>
                </ul>

                <button type="submit">Wykonaj!</button>
            </form>
            <?php } ?>


            <form class="q raportForm" method="GET" action="raport.php">
                <input type="hidden" name="where" id="where2">
                <input type="hidden" name="className" value="<?php echo $_GET['class'] ?>">
                <h2 id="raportHeader">Wygeneruj raport</h2>
                <ul class="raport">
                    <li>
                        <input id="raptoday" name="time" value="today" type="radio" checked>
                        <label for="raptoday">Dzisiaj</label>
                    </li>
                    <li>
                        <input id="rapperiod" name="time" value="period" type="radio">
                        <label for="rapperiod">Przedział czasowy</label>
                        <label class="auxilia">
                            Od: <input type="date" name="after">
                            Do: <input type="date" name="before">
                        </label>
                    </li>
                    <li>
                        <input id="rapall" name="time" value="all" type="radio">
                        <label for="rapall">Cała dostępna historia</label>
                    </li>
                </ul>
                <hr class="raport">
                <ul class="raport">
                    <li>
                        <input id="rapeveryone" name="who" value="everyone" type="radio" <?php echo $_GET[ 'class']? "": "checked" ?>>
                        <label for="rapeveryone">Dla wszystkich uczniów</label>
                    </li>
                    <li>
                        <input id="rapclass" name="who" value="class" type="radio" <?php echo $_GET[ 'class']? "checked": "" ?>>
                        <label for="rapclass">Ogranicz się do klasy:</label>
                        <label class="auxilia">
                            <select name="class">
                                <?php echo $classselect; ?>
                            </select>
                        </label>
                    </li>
                    <li>
                        <input id="rapwhere" name="who" value="where" type="radio">
                        <label for="rapwhere" class="forSelectedRaport">Dla zaznaczonych uczniów</label>
                    </li>
                    <li>
                        <input id="rapuser" name="who" value="user" type="radio">
                        <label for="rapuser" class="forUserRaport">Dla wybranego ucznia</label>
                        <input type="hidden" name="user" id="userRaport">
                    </li>
                </ul>

                <button class="raport" type="submit">Wykonaj!</button>
            </form>




            <form class="q addUsersForm" method="post" action="../php_db/db_adm_add_users.php">
                <h2 id="addUsersHeader">Dodaj klasę / uczniów</h2>
                <label id="getCode" class="addUsers">Nazwa klasy: <input name="className" type="text" value="<?php echo $_GET['class'] ?>"></label>
                <table id="userTable" class="labellike addUsers">
                    <tr>
                        <th>Kod ucznia</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                    </tr>

                </table>
                <label class="addUsers" id="addMoreRows">Dodaj więcej wierszy</label>
                <button type="submit" class="addUsers">Wykonaj!</button>


            </form>
    </div>
</body>
