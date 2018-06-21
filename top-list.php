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
        <title>Tippspiel - Besteliste</title>
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
        <script src="js/toplist.js"></script>

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
                <li role="presentation" id="btn-group-list"><a href="#"><i class="glyphicon glyphicon glyphicon-th-large"></i> Gruppentabelle</a></li>
                <li role="presentation" id="btn-top-list" class="active"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Bestenliste</a></li>
                <li role="presentation" id="btn-controlling" class="hidden"><a href="#"><i class="fa fa-cog"></i> Verwaltung</a></li>
                <li role="presentation" id="btn-logout"><a href="#" style="color: #D52B1E"><i class="glyphicon glyphicon-off"></i> logout</a></li>
            </ul>
        </div>

        <div class="container main-content" id="main-content">

            <!--<div class="alert alert-success" role="alert"><i class="fa fa-futbol-o" aria-hidden="true"></i> <strong>LA GRANDE FINALE</strong> Danke für eure rege Teilnahme. <strong>GLÜCKWUNSCH dem Gewinner</strong>. Ich hoffe wir können bei der WM in 2 Jahren mehr Leute animieren teilzunehmen und wünsche mir natürlich wieder so eine tolle Tippspielrunde. Natürlich nur wenn ihr auch wollt. Für Kritik, Wünsche, Anregungen und Lob könnt ihr mir natürlich <a href="mailto:danielkuenkel@googlemail.com?subject=Tippspiel">gerne schreiben</a>. Die Daten werden dann zeitnah von mir gelöscht. Tschüss und bis zum nächsten Mal. Euer Daniel.</div>-->
            <div class="alert-space alert-general-error"></div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bestenliste</h3>
                </div>
                <div class="panel-body">
                    <div id="btn-toggle-point-summary" class="text-center"><i class="fa fa-plus-circle"></i> Punkteberechnung</div>
                    <div id="point-summary" class="hidden">
                        <p><strong>Tippspiel</strong></p>
                        <p><span class="badge">5 Punkte</span> gibt es für einen exakten Tipp.</p>
                        <p><span class="badge">3 Punkte</span> gibt es für eine Verhältnisdifferenz von 1nem Tor. Beispiel: Ergebnis ist 3 : 1, getippt wurde 2 : 0 oder 4 : 2.</p>
                        <p><span class="badge">2 Punkte</span> gibt es für eine Verhältnisdifferenz von 2 Toren. Beispiel: Ergebnis ist 3 : 2, getippt wurde 1 : 0 oder 5 : 4.</p>
                        <p><span class="badge">1 Punkt</span> gibt es, wenn die Tendenz richtig getippt wurde. Beispiel: Ergebnis ist 3 : 2, getippt wurde 3 : 1 oder 4 : 2.</p>
                        <p><span class="badge">0 Punkte</span> gibt es für alle weiteren Tipps.</p>
                        <hr>
                        <p><strong>Zusatzpunkte</strong></p>
                        <p><span class="badge">4 Punkte</span> gibt es zusätzlich für einen richtigen Tipp auf den Partiegewinner. Hier ist egal welches Punkteverhältnis herrscht.</p>
                        <hr>
                        <p><strong>Siegertipp</strong></p>
                        <p><span class="badge">20 Punkte</span> gibt es zusätzlich für den Tipp des WM-Siegers.</p>
                    </div>

                    <div style="margin-top: 25px;">
                        <div class="alert-space alert-general-error"></div>
                        <div class="alert-space alert-no-tips"></div>
                        <div class="alert-space alert-no-results"></div>
                    </div>
                </div>
                
                <div class="panel-body hidden" id="static-chart-container">
                    <canvas id="static-chart"></canvas>
                </div>

                <table class="table table-striped hidden" id="panel-top-list">
                    <thead>
                        <tr>
                            <th><a href='#' id="togglePlace" class="DESC">Platz <span class="glyphicon glyphicon-triangle-bottom"></span></a></th>
                            <th>Benutzername</th>
                            <th>Punkte</th>
                        </tr>
                    </thead>
                    <tbody id="table-list"></tbody>
                </table>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Tipps der einzelnen Mitspieler</h3>
                </div>
                <div class="panel-body">
                    <div style="margin-top: 25px;">
                        <div class="alert-space alert-no-results"></div>
                    </div>
                </div>

                <table class="table hidden" id="panel-tipp-list">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th class="text-center">Tipp</th>
                            <th class="text-center">Ergebnis</th>
                            <th class="text-center">Punkte</th>
                            <!--<th>Zusatz</th>-->
                        </tr>
                    </thead>
                    <tbody id="tipp-list"></tbody>
                </table>
            </div>

        </div>

        <script>
            $(document).ready(function () {
                getToplist();

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
                window.location.replace('group-list.php');
            });

            $('#btn-top-list').on('click', function (event) {
                event.preventDefault();
            });

            $('#btn-logout').on('click', function (event) {
                event.preventDefault();
                clearLocalItems();
                window.location.replace('includes/logout.php');
            });

            $('#togglePlace').on('click', function (event) {
                event.preventDefault();
                if ($(this).hasClass('ASC')) {
                    $(this).find('.glyphicon').removeClass('glyphicon-triangle-top');
                    $(this).find('.glyphicon').addClass('glyphicon-triangle-bottom');
                    $(this).removeClass('ASC');
                    $(this).addClass('DESC');
                    renderList(currentList, true);
                } else {
                    $(this).find('.glyphicon').removeClass('glyphicon-triangle-bottom');
                    $(this).find('.glyphicon').addClass('glyphicon-triangle-top');
                    $(this).removeClass('DESC');
                    $(this).addClass('ASC');
                    renderList(currentList, false);
                }
            });
        </script>

    </body>
</html>