<?php echo "<!doctype html>"; ?>
<html>

<head>
    <title>Egzamin zawodowy: Raport</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../3.css">
    <link rel="stylesheet" type="text/css" href="../blue/style.css">
    <script defer src="../fontawesome-all.js"></script>
    <script src="../jquery-3.3.1.min.js"></script>
    <script src="../Chart.min.js"></script>
    <script src="../jquery.tablesorter.min.js"></script>
    <script>
        var chart;
        var graphsettings = {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: '',
                    data: [],
                    backgroundColor: 'transparent',
                    borderColor: 'hotpink',
                    borderWidth: 4,
                    spanGaps: true
                }]
            },
            options: {
                legend: {
                    display: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            min: 0,
                            max: 40
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            display: false
                        }
                    }]
                }
            }
        }

        function look(e) {
            var row = $(e.currentTarget).children().parents().filter("tr");
            window.location = "../check_answers.php?previous=javascript:history.back()&examtype=" + row.attr("data-type") + "&" + row.attr("data-string");
        }

        function updateTable() {
            $.ajax({
                url: "../php_db/db_generate_raport.php" + window.location.search,
                success: function(result) {
                    graphsettings.data.labels = [];
                    graphsettings.data.datasets[0].data = []
                    $("tbody").html(result);
                    chart.update();
                }
            });
        }

        $(function() {
            $("tbody tr").dblclick(look);
            $("tbody tr .look").click(look);
            $("#resultstable").tablesorter();
            setInterval(updateTable, 10000);
        })

    </script>
</head>

<body>
    <div class="top">
        <h1><a href="javascript:history.back()"><i class="fas fa-arrow-left"></i> Raport</a></h1>
        <table id="resultstable" class="tablesorter">
            <thead>
                <th>Data i godzina</th>
                <th>Rodzaj</th>
                <th>Uczeń</th>
                <th>Klasa</th>
                <th>Wynik</th>
                <th>Opcje</th>
            </thead>
            <tbody>
                <?php require "../php_db/db_generate_raport.php"; ?>
            </tbody>
        </table>





        <?php if($_GET["who"]=="user"){ ?>
        <canvas id="myChart" width="768" height="512"></canvas>
        <script>
            var ctx = document.getElementById("myChart").getContext('2d');
            var chart = new Chart(ctx, graphsettings);

        </script>
        <?php } ?>
    </div>
</body>

</html>
