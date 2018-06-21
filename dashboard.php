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

        <script src="js/contants.js"></script>
        <script src="js/storage.js"></script>
        <script src="js/alert.js"></script>
        <script src="js/functions.js"></script>
        <script src="js/dashboard.js"></script>

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
        <div id="alert-container" class="hidden">
            <div class="alert alert-danger" id="general-error" role="alert"><i class="glyphicon glyphicon-alert"></i> Es ist ein unbekannter Fehler aufgetreten!</div>
            <div class="alert alert-danger" id="tip-exists" role="alert"><span><i class="glyphicon glyphicon-alert"></i> Du hast bereits für dieses Spiel getippt. </span>
                <button class="btn btn-danger btn-reload-dashboard"><i class="glyphicon glyphicon-import"></i> Tipp laden</button>
            </div>
        </div>

        <div class="panel panel-default hidden root" id="dashboard-schedule-item" style="margin-bottom: 20px;">
            <div class="panel-heading general-info" style="padding: 0">
                <div class="row done hidden" style="padding: 7px 12px 7px 12px">
                    <div class="col-xs-3 col-sm-4 participator-a">
                        <img class="flag" src="" style="width: 20px; height: auto;"><br/>
                        <div class="country" style="font-size: 7pt;"></div>
                    </div>
                    <div class="col-xs-6 col-sm-4 text-center" style="margin: 0; padding: 0; margin-top: -2px;">
                        <div>
                            <span style="font-size: 7pt">ES: <span class="result" style="font-weight: bold;"><span class="goals-a">-</span>:<span class="goals-b">-</span></span>, </span>
                            <span style="font-size: 7pt">TP: <span class="user-tip-text" style="font-weight: bold;"><span class="goals-a">-</span>:<span class="goals-b">-</span></span></span>
                        </div>
                        <div class="badge pointsDone" style="margin-top: 1px;">Punkte</div>
                    </div>
                    <div class="col-xs-3 col-sm-4 participator-b text-right">
                        <img class="flag" src="" style="width: 20px; height: auto;"><br/>
                        <div class="country" style="font-size: 7pt;"></div>
                    </div>
                </div>

                <div class="row actual" style="padding: 7px 12px 8px 12px">
                    <div class="col-xs-6 col-sm-4 participator-a">
                        <img class="flag" src="" style="width: 50px; height: auto;"><br/>
                        <div class="country"></div>
                    </div>
                    <div class="col-xs-6 col-sm-push-4 col-sm-4 participator-b text-right">
                        <img class="flag" src="" style="width: 50px; height: auto;"><br/>
                        <div class="country"></div>
                    </div>
                    <div class="col-xs-12 col-sm-pull-4 col-sm-4 text-center">
                        <div class="time-left hidden"></div>
                        <div class="date">Datum</div>
                        <div class="time">Uhrzeit</div>
                        <div class="location">Lokation</div>
                        <img class="tv" src="" style="width: 45px; height: auto; margin-top: 8px;">
                        <div class="result-header hidden">Endergebnis</div>
                        <div class="result hidden" style="font-weight: bold; font-size: 21pt; margin-top: 8px;"><span class="goals-a">-</span> : <span class="goals-b">-</span></div>
                    </div>
                </div>
                <div class="progress hidden" style="padding: 0; margin: 0; border-radius: 0px; bottom: 0px; height: 6px;">
                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    </div>
                </div>
            </div>
            <div class="panel-body user-tip hidden">

                <div class="alert-space alert-tip-exists"></div>

                <div class="text-center tip-overview">
                    <button class="btn btn-info hidden btn-add-tip">Jetzt tippen</button>
                    <span class="no-tip-submitted" style="color: brown"><i class="glyphicon glyphicon-alert"></i> Kein Tipp abgegeben</span>
                    <div class="user-tip-container hidden">
                        <span style="font-size: 11pt;">Dein Tipp <span class="valuation hidden">war <span class="tip-was"></span> <i class="valuation-icon"></i> <span class="badge points"></span></span></span><br/>
                        <span style="font-size: 11pt;" class="winner-tip-correct hidden">Du erhälst für deinen Tipp auf den Sieger zusätzlich <span class="badge">4 Punkte</span></span>
                        <div class="user-tip-text" style="font-weight: bold; font-size: 19pt"><span class="goals-a">-</span> : <span class="goals-b">-</span></div>
                        <div class="waitingForResult hidden"><i class="glyphicon glyphicon-time"></i> Auf Resultate warten …</div>
                    </div>
                </div>

                <div class="row submit-tip-form hidden">

                    <div class="col-xs-6 col-sm-4">

                        <div class="simple-stepper" style="max-width: 120px;">
                            <input type="text" class="form-control readonly text-center stepper-text goal-a" value="0" style="border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
                            <div class="btn-group btn-group-justified" role="group">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-stepper-decrease" value="0" style="border-top-left-radius: 0px;">
                                        <span class="glyphicon glyphicon-minus"></span><span class="sr-only">weniger</span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-stepper-increase" value="20" style="border-top-right-radius: 0px;">
                                        <span class="glyphicon glyphicon-plus"></span><span class="sr-only">mehr</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-6 col-sm-push-4 col-sm-4 text-right">

                        <div class="simple-stepper pull-right" style="max-width: 120px;">
                            <input type="text" class="form-control readonly text-center stepper-text goal-b" value="0" style="border-bottom-left-radius: 0px; border-bottom-right-radius: 0px;">
                            <div class="btn-group btn-group-justified" role="group">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-stepper-decrease" value="0" style="border-top-left-radius: 0px;">
                                        <span class="glyphicon glyphicon-minus"></span><span class="sr-only">weniger</span>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-stepper-increase" value="20" style="border-top-right-radius: 0px;">
                                        <span class="glyphicon glyphicon-plus"></span><span class="sr-only">mehr</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="col-xs-12 col-sm-pull-4 col-sm-4 text-center" style="margin-top: 17px">
                        <div class="btn-group tip-controls">
                            <button class="btn btn-danger btn-cancel-submit-tip"><i class="glyphicon glyphicon-remove"></i> <span class="hidden-md">Abbrechen</span></button>
                            <button class="btn btn-success btn-submit-tip"><i class="fa fa-check"></i> <span class="">Tippen</span></button>
                        </div>
                    </div>

                </div>

            </div>
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
                <li role="presentation" id="btn-dashboard" class="active"><a href="#"><i class="fa fa-soccer-ball-o"></i> Tippspiel</a></li>
                <li role="presentation" id="btn-winner"><a href="#"><i class="fa fa-heart"></i> Siegertipp</a></li>
                <li role="presentation" id="btn-group-list"><a href="#"><i class="glyphicon glyphicon glyphicon-th-large"></i> Gruppentabelle</a></li>
                <li role="presentation" id="btn-top-list"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Bestenliste</a></li>
                <li role="presentation" id="btn-controlling" class="hidden"><a href="#"><i class="fa fa-cog"></i> Verwaltung</a></li>
                <li role="presentation" id="btn-logout"><a href="#" style="color: #D52B1E"><i class="glyphicon glyphicon-off"></i> logout</a></li>
            </ul>
        </div>

        <div class="container main-content" id="main-content">

            <!--<div class="alert alert-success" role="alert"><i class="fa fa-futbol-o" aria-hidden="true"></i> <strong>LA GRANDE FINALE</strong> Danke für eure rege Teilnahme. <strong>GLÜCKWUNSCH dem Gewinner</strong>. Ich hoffe wir können bei der WM in 2 Jahren mehr Leute animieren teilzunehmen und wünsche mir natürlich wieder so eine tolle Tippspielrunde. Natürlich nur wenn ihr auch wollt. Für Kritik, Wünsche, Anregungen und Lob könnt ihr mir natürlich <a href="mailto:danielkuenkel@googlemail.com?subject=Tippspiel">gerne schreiben</a>. Die Daten werden dann zeitnah von mir gelöscht. Tschüss und bis zum nächsten Mal. Euer Daniel.</div>-->
            <div class="alert-space alert-general-error"></div>

            <div class="row">
                <div class="col-md-4">
                    <div class="tip-content panel panel-default" id="general-overview">
                        <div class="panel-heading" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px">
                            <h3 class="panel-title">Zusammenfassung</h3>
                            <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                            <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body panel-body-collapse hidden" id="abstract">
                            <p><span class="badge" id="games-left"></span> Partien von insgesamt <span class="badge" id="games-total"></span> übrig</p>
                            <p><span class="badge" id="submitted-tips"></span> <span id="submitted-tips-of">Tipps von</span> <span class="badge" id="tips-total"></span> abgegeben</p>
                            <p>Insgesamt <span class="badge" id="current-score"></span> Punkte gesammelt</span>
                            <p>Es sind maximal <span class="badge" id="total-score"></span> Punkte möglich</p>
                            <p style="font-weight: bold">Im Jackpot: <span id="total-jackpot"></span>,00 EUR</p>
                            <div id="country-games" style="margin-top: 20px;">
                                <div class="input-group">
                                    <span class="input-group-addon">Wann spielt?</span>
                                    <input class="form-control item-input-text option-country show-dropdown readonly" type="text" value=""/>
                                    <div class="input-group-btn select countrySelect" role="group">
                                        <button class="btn btn-default dropdown-toggle disabled" type="button" data-toggle="dropdown"><span class="chosen hidden" id="unselected"></span><span class="caret"></span></button>
                                        <ul class="dropdown-menu option dropdown-menu-right" role="menu"></ul>
                                    </div>
                                </div>
                                <div class="country-playing-container"  style="margin-top: 10px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="tip-content panel panel-default" id="general-rules">
                        <div class="panel-heading" style="border-bottom-left-radius: 10px; border-bottom-right-radius: 10px">
                            <h3 class="panel-title">Anleitung & Regeln</h3>
                            <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                            <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body panel-body-collapse hidden">
                            <ul style="margin-left: 17px; padding: 0;">
                                <li>Tippen ist bis zum Anpfiff des jeweiligen Spiels möglich. Wird bis dahin kein Tipp abgegeben, wird dies nicht gewertet.</li>
                                <li>Ein Tipp darf nur einmal abgegeben werden.</li>
                                <li>Um einen Tipp abzugeben, klicke zurerst auf <button class="btn btn-xs btn-info">Jetzt tippen</button></li>
                                <li>Trage deinen Tipp ein, indem du für jedes Land die Tore mit <i class="glyphicon glyphicon-plus"></i> oder <i class="glyphicon glyphicon-minus"></i> auswählst.</li>
                                <li>Mit einem Klick auf <button class="btn btn-xs btn-success"><i class="glyphicon glyphicon-ok"></i></button> wird dein Tipp abgesendet und gespeichert.</li>
                                <li>Dein Tipp wird nach dem erfolgreichen Speichern direkt für das jeweilige Spiel angezeigt.</li>
                                <li>Tipps der anderenTipper werden nach Anpfiff der jeweiligen Partie in der Bestenliste eingeblendet.</li>
                                <li>Die Resultate werden nach einer Partie angezeigt.</li>
                                <li>Zusätzlich kannst du einen <button class="btn btn-xs btn-success btn-winner"><i class="fa fa-heart"></i> Tipp</button> auf den WM-Sieger abgeben.</li>
                                <li>Der Gewinner des Tippspiels bekommt am Ende den Jackpot ausgezahlt.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="tip-content panel panel-default" id="general-participants">
                        <div class="panel-heading">
                            <h3 class="panel-title">Teilnehmer</h3>
                            <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right hidden"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                            <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body panel-body-collapse">
                        </div>
                    </div>
                </div>
                <div class="col-md-8">

                    <div class="tip-content" id="preliminary">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 style="margin-top: 0px">Gruppenspiele 
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                </h3>
                                <hr class="hr-dark" style="margin: 0; margin-bottom: 10px">
                                <div class="timer-text hidden"></div>
                                <div class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></div>
                                <div class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel-body-collapse hidden" id="panel-preliminary"></div>
                            </div>
                        </div>


                    </div>
                    <!--                    <div class="panel panel-default" id="preliminary">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Gruppenspiele <span class="timer-text hidden"></span><span class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></span><span class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></span></h3>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body panel-body-collapse hidden" id="panel-preliminary">
                                            </div>
                                        </div>-->

                    <div class="tip-content" id="lastSixteen">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3>Achtefinale 
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                </h3>
                                <hr class="hr-dark" style="margin: 0; margin-bottom: 10px">
                                <div class="timer-text hidden"></div>
                                <div class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></div>
                                <div class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel-body-collapse hidden" id="panel-lastSixteen"></div>
                            </div>
                        </div>
                        <!--<div class="panel panel-default" id="lastSixteen">-->
                        <!--<div class="panel-heading">-->


                    </div>

                    <!--</div>-->

                    <div class="tip-content" id="quarterfinals">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3>Viertelfinale 
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                </h3>
                                <hr class="hr-dark" style="margin: 0; margin-bottom: 10px">
                                <div class="timer-text hidden"></div>
                                <div class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></div>
                                <div class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel-body-collapse hidden" id="panel-quarterfinals"></div>
                            </div>
                        </div>
                    </div>

                    <!--                    <div class="panel panel-default" id="quarterfinals">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Viertelfinale <span class="timer-text hidden"></span><span class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></span><span class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></span></h3>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body panel-body-collapse hidden" id="panel-quarterfinals">
                                            </div>
                                        </div>-->

                    <div class="tip-content" id="semifinals">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3>Halbfinale 
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                </h3>
                                <hr class="hr-dark" style="margin: 0; margin-bottom: 10px">
                                <div class="timer-text hidden"></div>
                                <div class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></div>
                                <div class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel-body-collapse hidden" id="panel-semifinals"></div>
                            </div>
                        </div>
                    </div>

                    <!--                    <div class="panel panel-default" id="semifinals">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Halbfinale <span class="timer-text hidden"></span><span class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></span><span class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></span></h3>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body panel-body-collapse hidden" id="panel-semifinals">
                                            </div>
                                        </div>-->

                    <div class="tip-content" id="smallfinal">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3>Kleines Finale 
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                </h3>
                                <hr class="hr-dark" style="margin: 0; margin-bottom: 10px">
                                <div class="timer-text hidden"></div>
                                <div class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></div>
                                <div class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel-body-collapse hidden" id="panel-smallfinal"></div>
                            </div>
                        </div>
                    </div>

                    <!--                    <div class="panel panel-default" id="smallfinal">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Kleines Finale <span class="timer-text hidden"></span><span class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></span><span class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></span></h3>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body panel-body-collapse hidden" id="panel-smallfinal">
                                            </div>
                                        </div>-->

                    <div class="tip-content" id="final">
                        <div class="row">
                            <div class="col-xs-12"> 
                                <h3>Finale
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                    <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                </h3>
                                <hr class="hr-dark" style="margin: 0; margin-bottom: 10px">
                                <div class="timer-text hidden"></div>
                                <div class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></div>
                                <div class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></div>
                            </div>
                            <div class="col-xs-12" style="margin-top: 10px;">
                                <div class="panel-body-collapse hidden" id="panel-final"></div>
                            </div>
                        </div>
                    </div>

                    <!--                    <div class="panel panel-default" id="final">
                                            <div class="panel-heading">
                                                <h3 class="panel-title">Finale <span class="timer-text hidden"></span><span class="games-finished hidden">beendet <i class="glyphicon glyphicon-ban-circle"></i></span><span class="games-active hidden">aktiv <i class="glyphicon glyphicon-send"></i></span></h3>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-show-panel-body pull-right"><span>einblenden</span> <i class="glyphicon glyphicon-plus-sign"></i></div>
                                                <div class="btn btn-xs btn-default btn-panel-heading btn-hide-panel-body pull-right hidden"><span>ausblenden</span> <i class="glyphicon glyphicon-minus-sign"></i></div>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="panel-body panel-body-collapse hidden" id="panel-final">
                                            </div>
                                        </div>-->
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function () {
                getSchedule();
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
            });

            $('#btn-winner, .btn-winner').on('click', function (event) {
                event.preventDefault();
                window.location.replace('winner.php');
            });

            $('#btn-group-list').on('click', function (event) {
                event.preventDefault();
                window.location.replace('group-list.php');
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

            $('.btn-hide-panel-body').on('click', function (event) {
                event.preventDefault();
                $(this).addClass('hidden');
                $(this).closest('.tip-content').find('.btn-show-panel-body').removeClass('hidden');
                hidePanelBody($(this).closest('.tip-content').find('.panel-body-collapse'));
                saveOverview();
            });

            function hidePanelBody(body) {
                $(body).addClass('hidden');
                $(body).closest('.panel').find('.panel-heading').css({borderBottomLeftRadius: '10px', borderBottomRightRadius: '10px'});
            }

            $('.btn-show-panel-body').on('click', function (event) {
                event.preventDefault();
                $(this).addClass('hidden');
                $(this).closest('.tip-content').find('.btn-hide-panel-body').removeClass('hidden');
                showPanelBody($(this).closest('.tip-content').find('.panel-body-collapse'));
                saveOverview();
            });

            function showPanelBody(body) {
                $(body).removeClass('hidden');
                $(body).closest('.panel').find('.panel-heading').css({borderBottomLeftRadius: '', borderBottomRightRadius: ''});
            }
        </script>

    </body>
</html>