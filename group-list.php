<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

session_start();
$checkLogin = login_check($mysqli);
if ($checkLogin === 'not-payed-yet') {
    header('Location: account-locked.php');
} else if ($checkLogin == false) {
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Tippspiel - Gruppentabelle</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
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
        <script src="js/chance.min.js"></script>

        <script src="js/contants.js"></script>
        <script src="js/storage.js"></script>
        <script src="js/alert.js"></script>
        <script src="js/functions.js"></script>
        <script src="js/grouplist.js"></script>

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

        <!-- alerts -->
        <div id="alert-container" class="hidden">
            <div class="alert alert-danger" id="general-error" role="alert"><i class="glyphicon glyphicon-alert"></i> Es ist ein unbekannter Fehler aufgetreten!</div>
            <div class="alert alert-info" id="no-tips" role="alert"><i class="glyphicon glyphicon-alert"></i> Es wurden noch keine Tipps abgegeben!</div>
            <div class="alert alert-info" id="no-results" role="alert"><i class="glyphicon glyphicon-alert"></i> Es sind noch keine Resultate verfügbar!</div>
        </div>

        <!-- header content -->
        <div class="jumbotron jumbotron-dashboard text-center">
            <div class="container">
                <h1><i class="fa fa-soccer-ball-o"></i> KICK TIPP</h1>
            </div>
        </div>
        <div class="line">
            <div class="line-white"></div>
            <div class="line-blue"></div>
            <div class="line-red"></div>
        </div>

        <div id="navigation-line">
            <ul class="nav nav-pills nav-justified">
                <li role="presentation" id="btn-dashboard"><a href="#"><i class="fa fa-soccer-ball-o"></i> Tippspiel</a></li>
                <li role="presentation" id="btn-winner"><a href="#"><i class="fa fa-heart"></i> Siegertipp</a></li>
                <li role="presentation" id="btn-group-list" class="active"><a href="#"><i class="glyphicon glyphicon glyphicon-th-large"></i> Gruppentabelle</a></li>
                <li role="presentation" id="btn-top-list"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Bestenliste</a></li>
                <li role="presentation" id="btn-controlling" class="hidden"><a href="#"><i class="fa fa-cog"></i> Verwaltung</a></li>
                <li role="presentation" id="btn-logout"><a href="#" style="color: #D52B1E"><i class="glyphicon glyphicon-off"></i> logout</a></li>
            </ul>
        </div>

        <div class="container main-content" id="main-content">

            <!--<div class="alert alert-success" role="alert"><i class="fa fa-futbol-o" aria-hidden="true"></i> <strong>LA GRANDE FINALE</strong> Danke für eure rege Teilnahme. <strong>GLÜCKWUNSCH dem Gewinner</strong>. Ich hoffe wir können bei der WM in 2 Jahren mehr Leute animieren teilzunehmen und wünsche mir natürlich wieder so eine tolle Tippspielrunde. Natürlich nur wenn ihr auch wollt. Für Kritik, Wünsche, Anregungen und Lob könnt ihr mir natürlich <a href="mailto:danielkuenkel@googlemail.com?subject=Tippspiel">gerne schreiben</a>. Die Daten werden dann zeitnah von mir gelöscht. Tschüss und bis zum nächsten Mal. Euer Daniel.</div>-->
            <div class="alert-space alert-general-error"></div>

            <div class="col-xs-6 col-md-3 hidden text-center" id="country-container-item">
                <img class="flag" src="" style="width: 64px; height: auto;"><br/>
                <p class="country-name"></p>
            </div>

            <div class="hidden" id="group-list-item">
                <div class="info">
                    <div class="page-header">
                        <h2>Gruppe <span class="group-number"></span></h2>
                    </div>
                    <div class="info-text">
                        <div class="row country-container"></div>
                    </div>
                </div>
            </div>

            <div id="group-list"></div>

        </div>

        <script>
            $(document).ready(function () {
                getGrouplist();
                
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

            $('#btn-dashboard').on('click', function (event) {
                event.preventDefault();
                window.location.replace('dashboard.php');
            });

            $('#btn-winner').on('click', function (event) {
                event.preventDefault();
                window.location.replace('winner.php');
            });

            $('#btn-group-list').on('click', function (event) {
                event.preventDefault();
            });

            $('#btn-top-list').on('click', function (event) {
                event.preventDefault();
                window.location.replace('top-list.php');
            });

            $('#btn-logout').on('click', function (event) {
                event.preventDefault();
                clearLocalItems();
                window.location.replace('includes/logout.php');
            });
        </script>

    </body>
</html>