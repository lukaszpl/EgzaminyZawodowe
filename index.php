<?php echo "<!doctype html>"; ?>
<?php error_reporting(0) ?>

<head>
    <title>Egzamin zawodowy</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="3.css">
    <script defer src="fontawesome-all.js"></script>
    <script src="jquery-3.3.1.min.js"></script>
    <script>
        $(function() {
            $("#checkbox").prop('checked', false).click(function() {
                if ($("#checkbox").prop('checked')) {
                    $("#getCode").show().children().val("");
                } else {
                    $("#getCode").hide();
                }
            });
            $(".square").dblclick(function() {
                $("form").submit();
            })
            setInterval(timeCycle, 1000);
        });

        function timeCycle() {
            var originalTime = parseInt($("#remainingMinutes").text()) * 60 + parseInt($("#remainingSeconds").text()) - 1;
            if (originalTime == 0) {
                alert("Czas przeznaczony na egzamin upłynął.");
                $("form").submit();
            }
            $("#remainingMinutes").text(Math.floor(originalTime / 60));
            $("#remainingSeconds").text(Math.floor(originalTime % 60));
        }

    </script>
</head>

<body>

    <?php
	if($_GET['type'] == null){
        $spin=$_GET['you']== "spin"&&$_GET['me']== "round"?"fa-spin":"";
?>
        <form method="GET" class="top">
            <h1>Egzamin zawodowy</h1>
            <div class="q">
                <h2>Wybierz kwalifikację:</h2>
                <ul>
                    <li class="square">
                        <input id="-1" name="type" value="0" type="radio">
                        <label for="-1">
                            <div class="qualicon"><i class="fas <?php echo $spin ?> fa-microchip"></i></div>
                            <h3>E.12</h3>
                            <p>Ilość pytań: 40<br>Czas: 60 minut</p>
                        </label>
                    </li>
                    <li class="square">
                        <input id="-2" name="type" value="1" type="radio">
                        <label for="-2">
                            <div class="qualicon"><i class="fas <?php echo $spin ?> fa-sitemap"></i></div>
                            <h3>E.13</h3>
                            <p>Ilość pytań: 40<br>Czas: 60 minut</p></label>
                    </li>
                    <li class="square">
                        <input id="-3" name="type" value="2" type="radio">
                        <label for="-3">
                            <div class="qualicon"><i class="fas <?php echo $spin ?> fa-code"></i></div>
                            <h3>E.14</h3>
                            <p>Ilość pytań: 40<br>Czas: 60 minut</p></label>
                    </li>
                </ul>
                <label><input type="checkbox" id="checkbox">Zapisz wynik egzaminu</label>
                <label style="display: none;" id="getCode">Podaj kod ucznia: <input name="userCode" type="text"></label>
            </div>
            <button type="submit">Rozpocznij egzamin!</button>
        </form>
        <?php
	}else{      
		if($_GET['userCode']){
			require 'php_db/db_connect.php';	
			$code = $_GET['userCode'];
			$type = $_GET['type'];
			$sql = "SELECT * FROM users WHERE userCode = '{$code}'";
			$result = $connect->query($sql);
			if($result->num_rows <= 0){
				//alert nie ma takiego ucznia
				echo "<script>alert(\"Wybrany kod ucznia nie istnieje!\"); window.location.href = \"index.php\";</script>";
			}else{
				$row=$result->fetch_assoc();
				$canSolveBin=str_pad(decbin($row['canSolve']), 100, "0", STR_PAD_LEFT);
				if($canSolveBin[99-$type]==0){
					//alert zabroniono
					echo "<script>alert(\"Administrator zablokował Ci możliwość rozwiązywania tego testu!\"); window.location.href = \"index.php\";</script>";

				}
			}
			
		}
		include("php_db/db_methods_html.php");
		echo GetRandomQuestions($_GET['type'], $_GET['userCode'], 40);
	}
?>
</body>
