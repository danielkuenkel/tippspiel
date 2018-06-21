<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

session_start();
//$checkLogin = login_check($mysqli);
//if ($checkLogin === 'not-payed-yet') {
//    header('Location: account-locked.php');
//} else if ($checkLogin == false) {
//    header('Location: index.php');
//}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Tippspiel - Dashboard</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">

        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.4.4/randomColor.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/TweenMax.min.js"></script>
        <script src="js/chance.min.js"></script>
        <script src="js/chartjs/Chart.min.js"></script>
        <script src="js/randomColor/randomColor.js"></script>

        <script src="js/contants.js"></script>
        <script src="js/storage.js"></script>
        <script src="js/alert.js"></script>
        <script src="js/functions.js"></script>
        <!--<script src="js/dashboard.js"></script>-->

        <!-- favicons -->
        <!--        <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
                <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
                <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
                <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
                <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
                <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
                <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
                <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
                <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
                <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
                <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
                <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
                <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
                <link rel="manifest" href="/manifest.json">
                <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
                <meta name="msapplication-TileColor" content="#da532c">
                <meta name="msapplication-TileImage" content="/mstile-144x144.png">
                <meta name="theme-color" content="#ffffff">-->
    </head>
    <body>
        <!-- ALERTS -->
        <div class="container">
            <div class="panel panel-default">
                <div class="panel-body">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

        </div>


        <script>
            $(document).ready(function () {
                getChartData(function (result) {
//                    console.log('result', result);
                    if (result.status === RESULT_SUCCESS) {
                        console.log(result);
                        var games = result.games;
                        var tips = result.tips;
                        var users = result.users;
                        setLocalItem(TIPS, tips);
                        setLocalItem(GAMES, games);
                        setLocalItem(USERS, users);
                        setLocalItem(COUNTRIES, result.countries);

                        var chartData = [];

                        for (var i = 0; i < games.length; i++) {
                            for (var j = 0; j < users.length; j++) {
                                var tip = getUserTip(games[i].id, users[j].id);

                                if (tip !== null) {
                                    var winnerTippedPoints = hasTippedWinnerCorrectly(tip) === true ? CORRECT_WINNER_TIP_POINTS : 0;
                                    var points = calculatePoints(tip) + winnerTippedPoints;
                                    chartData = updateChartData(chartData, points, users[j], games[i]);
                                } else {
                                    chartData = updateChartData(chartData, 0, users[j], games[i]);
                                }
                            }
                        }

                        renderChart(chartData);
                    }
                });

                var type = '<?php echo $_SESSION['type'] ?>';
                if (type === 'admin') {
                    $('#btn-controlling').removeClass('hidden');

                    $('#btn-controlling').on('click', function (event) {
                        event.preventDefault();
                        window.location.replace('controlling.php');
                    });
                } else {
                    $('#btn-controlling').remove();
                }

            });

            function getUserTip(gameId, userId) {
                var tips = getLocalItem(TIPS);
//                console.log(tips);
                for (var i = 0; i < tips.length; i++) {

                    if (parseInt(tips[i].gameId) === parseInt(gameId) && parseInt(tips[i].userId) === parseInt(userId)) {
//                        console.log(tips[i], gameId, userId);
                        return tips[i];
                    }
                }
                return null;
            }

            function updateChartData(chartData, points, user, game) {
                if (chartData && chartData.length > 0) {
                    for (var i = 0; i < chartData.length; i++) {
                        if (parseInt(chartData[i].userId) === parseInt(user.id)) {
                            chartData[i].points.push({gameId: game.id, sum: points + (chartData[i].points[chartData[i].points.length - 1].sum)});
                            return chartData;
                        }
                    }

                    chartData.push({userId: user.id, username: user.username, points: [{gameId: game.id, sum: points}]});
                    return chartData;
                } else {
                    chartData.push({userId: user.id, username: user.username, points: [{gameId: game.id, sum: points}]});
                    return chartData;
                }
            }

            function renderChart(chartData) {
                // Generate array with numbers - [x, y, z]
//                var color = ;

//                console.log(color);
                var colors = randomColor({luminosity: 'dark', count: chartData.length});
                var datasets = [];
                var labels = [];
                var startIndex = chartData[0].points.length - 8;
                console.log(startIndex, chartData[0].points.length);
                for (var i = 0; i < chartData.length; i++) {
                    var data = [];
                    for (var j = startIndex; j < chartData[i].points.length; j++) {
                        data.push(chartData[i].points[j].sum);
                        if (i === startIndex) {
                            var game = getGameById(chartData[i].points[j].gameId);
                            labels.push(getCountryByIso(game.participatorA).name + ' : ' + getCountryByIso(game.participatorB).name);
                        }
                    }

                    datasets.push({label: chartData[i].username, data: data, borderColor: colors[i], borderWidth: 2, fill: false, lineTension: 0});
                }

                var ctx = document.getElementById("myChart").getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }],
                            xAxes: [{
                                    ticks: {
                                        autoSkip: false,
                                        maxRotation: 90,
                                        minRotation: 90,
                                        top:10
                                    }
                                }]
                        }
                    }
                });
            }
        </script>

    </body>
</html>