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
        <title>Tippspiel - Verwaltung</title>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/1.18.5/TweenMax.min.js"></script>
        <script src="js/chance.min.js"></script>

        <script src="js/contants.js"></script>
        <script src="js/storage.js"></script>
        <script src="js/alert.js"></script>
        <script src="js/functions.js"></script>

        <!-- date picker sources -->
        <script src="js/moment/moment.js"></script>
        <link rel="stylesheet" href="js/bootstrap-datepicker/css/bootstrap-datetimepicker.min.css">
        <script src="js/bootstrap-datepicker/js/bootstrap-datetimepicker.min.js"></script>
        <script src="js/moment/locale/de.js" charset="UTF-8"></script>

    </head>
    <body>
        <!-- ALERTS -->
        <div id="alert-container" class="hidden">
            <div class="alert alert-danger" id="general-error" role="alert"><i class="glyphicon glyphicon-alert"></i> Es ist ein unbekannter Fehler aufgetreten!</div>
            <div class="alert alert-danger" id="tip-exists" role="alert"><span><i class="glyphicon glyphicon-alert"></i> Du hast bereits für dieses Spiel getippt. </span>
                <button class="btn btn-danger btn-reload-dashboard"><i class="glyphicon glyphicon-import"></i> Tipp laden</button>
            </div>
        </div>

        <!-- edit game modal -->
        <div id="editGameModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg root">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Partie anpassen</h4>
                    </div>
                    <div class="modal-body">

                        <div id="register-form">
                            <div class="alert-space alert-general-error"></div>
                            <div class="alert-space alert-missing-fields"></div>
                            <div class="alert-space alert-register-success"></div>

                            <div id="form-groups">
                                <div class="form-group">
                                    <label for="email">Datum & Uhrzeit</label>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div id="date-picker"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="select-location">
                                            <label>Austragungsort</label>
                                            <div class="input-group">
                                                <input class="form-control item-input-text option-select-location show-dropdown readonly" type="text" value=""/>
                                                <div class="input-group-btn select" role="group">
                                                    <button class="btn btn-default btn-shadow dropdown-toggle" type="button" data-toggle="dropdown"><span class="chosen hidden" id="unselected"></span><span class="caret"></span></button>
                                                    <ul class="dropdown-menu option dropdown-menu-right" role="menu">
                                                        <li id="jekatarinburg"><a href="#">Jekaterinburg</a></li>
                                                        <li id="kaliningrad"><a href="#">Kaliningrad</a></li>
                                                        <li id="kasan"><a href="#">Kasan</a></li>
                                                        <li id="moskau"><a href="#">Moskau</a></li>
                                                        <li id="nischniNowgorod"><a href="#">Nischni Nowgorod</a></li>
                                                        <li id="rostow"><a href="#">Rostow am Don</a></li>
                                                        <li id="samara"><a href="#">Samara</a></li>
                                                        <li id="saransk"><a href="#">Saransk</a></li>
                                                        <li id="sotschi"><a href="#">Sotschi</a></li>
                                                        <li id="stPetersburg"><a href="#">Sankt Petersburg</a></li>
                                                        <li id="wolgograd"><a href="#">Wolgograd</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group" id="select-round">
                                            <label>Runde</label>
                                            <div class="input-group">
                                                <input class="form-control item-input-text option-round show-dropdown readonly" type="text" value=""/>
                                                <div class="input-group-btn select" role="group">
                                                    <button class="btn btn-default btn-shadow dropdown-toggle" type="button" data-toggle="dropdown"><span class="chosen hidden" id="unselected"></span><span class="caret"></span></button>
                                                    <ul class="dropdown-menu option dropdown-menu-right" role="menu">
                                                        <li id="preliminary"><a href="#">Gruppenspiele</a></li>
                                                        <li id="lastSixteen"><a href="#">Achtelfinale</a></li>
                                                        <li id="quarterfinals"><a href="#">Viertelfinale</a></li>
                                                        <li id="semifinals"><a href="#">Halbfinale</a></li>
                                                        <li id="smallfinal"><a href="#">Kleines Finale</a></li>
                                                        <li id="final"><a href="#">Finale</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group" id="select-participator-a">
                                            <label>Mannschaft A</label>
                                            <div class="input-group">
                                                <input class="form-control item-input-text option-participator-a show-dropdown readonly" type="text" value=""/>
                                                <div class="input-group-btn select" role="group">
                                                    <button class="btn btn-default btn-shadow dropdown-toggle" type="button" data-toggle="dropdown"><span class="chosen hidden" id="unselected"></span><span class="caret"></span></button>
                                                    <ul class="dropdown-menu option dropdown-menu-right" role="menu">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group" id="select-participator-b">
                                            <label>Mannschaft B</label>
                                            <div class="input-group">
                                                <input class="form-control item-input-text option-participator-a show-dropdown readonly" type="text" value=""/>
                                                <div class="input-group-btn select" role="group">
                                                    <button class="btn btn-default btn-shadow dropdown-toggle" type="button" data-toggle="dropdown"><span class="chosen hidden" id="unselected"></span><span class="caret"></span></button>
                                                    <ul class="dropdown-menu option dropdown-menu-right" role="menu">
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group root" id="select-update-goals">

                                    <label style="margin-bottom: 5px">
                                        Tore aktualisieren?
                                    </label><br>

                                    <div class="btn-group" id="radio" style="margin: 0">
                                        <button class="btn btn-default btn-radio btn-option-checked" name="primary" id="no">
                                            <span id="icons" style="margin-right: 6px">
                                                <i class="fa fa-circle-thin hidden" id="normal"></i>
                                                <i class="fa fa-circle hidden" id="over"></i>
                                                <i class="fa fa-check-circle" id="checked"></i>
                                            </span>
                                            <span class="option-text">Nein</span>
                                        </button>
                                    </div>
                                    <div class="btn-group" id="radio" style="margin: 0">
                                        <button class="btn btn-default btn-radio" name="primary" id="yes">
                                            <span id="icons" style="margin-right: 6px">
                                                <i class="fa fa-circle-thin" id="normal"></i>
                                                <i class="fa fa-circle hidden" id="over"></i>
                                                <i class="fa fa-check-circle hidden" id="checked"></i>
                                            </span>
                                            <span class="option-text">Ja</span>
                                        </button>
                                    </div>
                                    <div class="btn-group" id="radio" style="margin: 0">
                                        <button class="btn btn-default btn-radio" name="primary" id="reset">
                                            <span id="icons" style="margin-right: 6px">
                                                <i class="fa fa-circle-thin" id="normal"></i>
                                                <i class="fa fa-circle hidden" id="over"></i>
                                                <i class="fa fa-check-circle hidden" id="checked"></i>
                                            </span>
                                            <span class="option-text">Zurücksetzen</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tore Mannschaft A</label>
                                            <div class="input-group simple-stepper" id="goals-participator-a" style="max-width: 100%;">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-decrease" value="0">
                                                        <span class="glyphicon glyphicon-minus"></span><span class="sr-only"><?php echo $lang->less ?></span>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control readonly text-center stepper-text" value="0">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-increase" value="1000">
                                                        <span class="glyphicon glyphicon-plus"></span><span class="sr-only"><?php echo $lang->more ?></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tore Mannschaft B</label>
                                            <div class="input-group simple-stepper" id="goals-participator-b" style="max-width: 100%;">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-decrease" value="0">
                                                        <span class="glyphicon glyphicon-minus"></span><span class="sr-only"><?php echo $lang->less ?></span>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control readonly text-center stepper-text" value="0">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-increase" value="1000">
                                                        <span class="glyphicon glyphicon-plus"></span><span class="sr-only"><?php echo $lang->more ?></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tore Mannschaft A (Elfmeter)</label>
                                            <div class="input-group simple-stepper" id="penalty-goals-participator-a" style="max-width: 100%;">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-decrease" value="0">
                                                        <span class="glyphicon glyphicon-minus"></span><span class="sr-only"><?php echo $lang->less ?></span>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control readonly text-center stepper-text" value="0">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-increase" value="1000">
                                                        <span class="glyphicon glyphicon-plus"></span><span class="sr-only"><?php echo $lang->more ?></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Tore Mannschaft B (Elfmeter)</label>
                                            <div class="input-group simple-stepper" id="penalty-goals-participator-b" style="max-width: 100%;">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-decrease" value="0">
                                                        <span class="glyphicon glyphicon-minus"></span><span class="sr-only"><?php echo $lang->less ?></span>
                                                    </button>
                                                </div>
                                                <input type="text" class="form-control readonly text-center stepper-text" value="0">
                                                <div class="input-group-btn">
                                                    <button type="button" class="btn btn-default btn-shadow btn-stepper-increase" value="1000">
                                                        <span class="glyphicon glyphicon-plus"></span><span class="sr-only"><?php echo $lang->more ?></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="form-group">
                            <button type="button" class="btn btn-block btn-danger" id="btn-delete-game" ><i class="fa fa-trash" aria-hidden="true"></i> <span class="btn-text">Löschen</span></button>
                        </div>

                        <div class="form-group hidden" id="check-delete-game">
                            <div class="btn-group btn-group-justified" role="group">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default" id="btn-submit-delete-game"><i class="fa fa-check" aria-hidden="true"></i> <span class="btn-text">Ja</span></button>
                                </div>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-default" id="btn-cancel-delete-game"><i class="fa fa-close"></i> <span class="btn-text">Abbrechen</span></button>
                                </div>
                            </div>
                        </div>



                        <div class="btn-group-vertical btn-block" role="group">
                            <button type="button" class="btn btn-default" id="btn-save-game"><i class="fa fa-save" aria-hidden="true"></i> <span class="btn-text">Speichern</span></button>
                            <button type="button" class="btn btn-default" data-dismiss='modal' id="btn-close-edit-game-modal" style="margin-left: 0"><i class="fa fa-close"></i> <span class="btn-text">Schließen</span></button>
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
                <li role="presentation" id="btn-dashboard"><a href="#"><i class="fa fa-soccer-ball-o"></i> Tippspiel</a></li>
                <li role="presentation" id="btn-winner"><a href="#"><i class="fa fa-heart"></i> Siegertipp</a></li>
                <li role="presentation" id="btn-group-list"><a href="#"><i class="glyphicon glyphicon glyphicon-th-large"></i> Gruppentabelle</a></li>
                <li role="presentation" id="btn-top-list"><a href="#"><i class="glyphicon glyphicon-th-list"></i> Bestenliste</a></li>
                <li role="presentation" id="btn-controlling" class="active"><a href="#"><i class="fa fa-cog"></i> Verwaltung</a></li>
                <li role="presentation" id="btn-logout"><a href="#" style="color: #D52B1E"><i class="glyphicon glyphicon-off"></i> logout</a></li>
            </ul>
        </div>

        <div class="container" id="main-content">
            <h3>Benutzer</h3>
            <hr class="hr-dark">
            <div class="list-group" id="users-list">
                <!--                <a href="#" class="list-group-item accountUnlocked">Cras justo odio</a>
                                <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
                                <a href="#" class="list-group-item">Morbi leo risus</a>
                                <a href="#" class="list-group-item">Porta ac consectetur ac</a>
                                <a href="#" class="list-group-item">Vestibulum at eros</a>-->
            </div>
            <!--<hr class="hr-dark">-->
        </div>

        <div class="container main-content" id="games-content" style="margin-top:50px">
            <button type="button" class="btn btn-success btn-lg btn-shadow btn-block" id="btn-add-game" style="border-radius: 10px"><i class="fa fa-plus"></i> Partie hinzufügen</button>

            <div id="preliminary" style="margin-bottom: 60px;">
                <h3>Gruppenspiele</h3>
                <hr class="hr-dark">
                <div class="games-list"></div>
            </div>
            <div id="lastSixteen" style="margin-bottom: 60px;">
                <h3>Achtefinale</h3>
                <hr class="hr-dark">
                <div class="games-list"></div>
            </div>
            <div id="quarterfinals" style="margin-bottom: 60px;">
                <h3>Viertelfinale</h3>
                <hr class="hr-dark">
                <div class="games-list"></div>
            </div>
            <div id="semifinals" style="margin-bottom: 60px;">
                <h3>Halbfinale</h3>
                <hr class="hr-dark">
                <div class="games-list"></div>
            </div>
            <div id="smallfinal" style="margin-bottom: 60px;">
                <h3>Kleines Finale</h3>
                <hr class="hr-dark">
                <div class="games-list"></div>
            </div>
            <div id="final">
                <h3>Finale</h3>
                <hr class="hr-dark">
                <div class="games-list"></div>
            </div>

        </div>

        <div class="panel panel-default hidden root game-edit-item" id="game-edit-item" style="margin-bottom: 20px;">
            <div class="panel-heading general-info" style="padding: 0; border-radius: 10px; cursor: pointer">
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
                        <!--<div class="time-left hidden"></div>-->
                        <div class="date">Datum</div>
                        <div class="time">Uhrzeit</div>
                        <div class="location">Lokation</div>
                        <img class="tv" src="" style="width: 45px; height: auto; margin-top: 8px;">
                        <div class="result-header">Endergebnis</div>
                        <div class="result" style="font-weight: bold; font-size: 21pt; margin-top: 8px;"><span class="goals-a">-</span> : <span class="goals-b">-</span></div>
                    </div>
                </div>
                <!--                <div class="progress hidden" style="padding: 0; margin: 0; border-radius: 0px; bottom: 0px; height: 6px;">
                                    <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                                    </div>
                                </div>-->
            </div>
            <!--            <div class="panel-body user-tip hidden">
            
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
            
                        </div>-->
        </div>

        <script>
            var currentEditGameId = null;
            var LOCATIONS = [
                {
                    id: 'jekatarinburg',
                    title: 'Jekaterinburg'
                },
                {
                    id: 'kaliningrad',
                    title: 'Kaliningrad'
                },
                {
                    id: 'kasan',
                    title: 'Kasan'
                },
                {
                    id: 'moskau',
                    title: 'Moskau'
                },
                {
                    id: 'nischniNowgorod',
                    title: 'Nischni Nowgorod'
                },
                {
                    id: 'rostow',
                    title: 'Rostow am Don'
                },
                {
                    id: 'samara',
                    title: 'Samara'
                },
                {
                    id: 'saransk',
                    title: 'Saransk'
                },
                {
                    id: 'sotschi',
                    title: 'Sotschi'
                },
                {
                    id: 'stPetersburg',
                    title: 'Sankt Petersburg'
                },
                {
                    id: 'wolgograd',
                    title: 'Wolgograd'
                }
            ];

            var ROUNDS = [
                {
                    id: 'preliminary',
                    title: 'Gruppenspiele'
                },
                {
                    id: 'lastSixteen',
                    title: 'Achtelfinale'
                },
                {
                    id: 'quarterfinals',
                    title: 'Viertelfinale'
                },
                {
                    id: 'semifinals',
                    title: 'Halbfinale'
                },
                {
                    id: 'smallfinal',
                    title: 'Kleines Finale'
                },
                {
                    id: 'final',
                    title: 'Finale'
                }
            ];

            $(document).ready(function () {
                var type = '<?php echo $_SESSION['type'] ?>';
                if (type !== 'admin') {
                    $('#btn-controlling').remove();
                    window.location.replace('dashboard.php');
                } else {
                    renderData();
                }
            });

            $('#btn-dashboard').on('click', function (event) {
                event.preventDefault();
                window.location.replace('dashboard.php');
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

            function renderData() {
                getUsers(function (users) {
                    $('#users-list').empty();
                    if (users && users.length > 0) {
                        renderUsers(users);
                    } else {

                    }
                });

                getGames(function (result) {
                    if (result.status === RESULT_SUCCESS) {
//                        console.log(result);
                        setLocalItem(GAMES, result.games);
                        setLocalItem(COUNTRIES, result.countries);
                        renderGames();
                    }
                });
            }

            function renderUsers(users) {
                for (var i = 0; i < users.length; i++) {
                    var item = document.createElement('a');
                    $(item).addClass('list-group-item');
                    $('#users-list').append(item);

                    var icon = document.createElement('i');
                    $(icon).addClass('fa');
                    $(icon).css({marginRight: '10px'});
                    $(item).append(icon);

                    var text = document.createElement('span');
                    $(text).text(users[i].username);
                    $(item).append(text);

                    if (parseInt(users[i].hasPayed) === 1) {
                        $(item).addClass('unlocked');
                        $(icon).addClass('accountUnlocked fa-unlock-alt');
                    } else {
                        $(icon).addClass('fa-lock');
                    }

                    $(item).unbind('click').bind('click', {id: users[i].id}, function (event) {
                        event.preventDefault();
                        var button = $(this);
                        lockButton(button, true);

                        var hasPayed = 1;
                        if ($(this).hasClass('unlocked')) {
                            hasPayed = 0;
                            $(this).find('.fa').removeClass('accountUnlocked fa-unlock-alt').addClass('fa-lock');
                            $(this).removeClass('unlocked');
                        } else {
                            $(this).find('.fa').removeClass('fa-lock').addClass('accountUnlocked fa-unlock-alt');
                            $(this).addClass('unlocked');
                        }

                        updateUser({id: event.data.id, hasPayed: hasPayed}, function (result) {
//                            console.log(result);
                            unlockButton(button, true);
                        });
                    });
                }
            }

            function renderGames() {
                var games = getLocalItem(GAMES);
                $('#games-content').find('.games-list').empty();

                for (var i = 0; i < games.length; i++) {
                    var item = $('#game-edit-item').clone().removeAttr('id').removeClass('hidden');
                    $(item).attr('data-game-id', games[i].id);
                    $(item).attr('data-time', games[i].timestamp);
//                    console.log(games[i].round, $('#games-content').find('#' + games[i].round))
                    $('#games-content').find('#' + games[i].round + ' .games-list').append(item);

                    if (games[i].timestamp !== "") {
                        item.find('.date').text(getDate(games[i].timestamp));
                        item.find('.time').text(getTime(games[i].timestamp));

//                        item.find('.time-left').css('color', 'black');

                        if (games[i].timestamp * 1000 > new Date()) {
//                            var timeLeft = getTimeLeftForTimestamp(games[i].timestamp * 1000);
//                            item.find('.time-left').removeClass('hidden');
//                            item.find('.time-left').text('in ' + (timeLeft.days === 1 ? timeLeft.days + ' Tag ' : timeLeft.days + " Tagen ") + timeLeft.hours + " Std. " + timeLeft.minutes + ' Min.');

//                            if (timeLeft.days === 0) {
////                                item.find('.progress').removeClass('hidden');
////                                item.find('.progress-bar').removeClass('progress-bar-info');
////                                item.find('.progress-bar').removeClass('progress-bar-default');
////                                item.find('.progress-bar').removeClass('progress-bar-danger');
//                                if (timeLeft.hours >= 12) {
////                                    item.find('.progress-bar').addClass('progress-bar-info');
//                                } else if (timeLeft.hours >= 1) {
////                                    item.find('.progress-bar').addClass('progress-bar-default');
//                                } else if (timeLeft.hours >= 0) {
////                                    item.find('.time-left').css('color', 'brown');
////                                    item.find('.progress-bar').addClass('progress-bar-danger');
//                                }
//                            }
                        } else if (games[i].goalsParticipatorA === null && games[i].goalsParticipatorB === null) {
//                            item.find('.progress').removeClass('hidden');
//                            item.find('.progress-bar').removeClass('progress-bar-info');
//                            item.find('.progress-bar').addClass('progress-bar-warning');

                            item.find('.result .goals-a').text('-');
                            item.find('.result .goals-b').text('-');
                        }
                    }

                    if (games[i].location !== "") {
                        item.find('.location').text(games[i].location);
                    }

                    if (games[i].goalsParticipatorA !== null && games[i].goalsParticipatorB !== null) {
                        if (games[i].goalsPenaltyParticipatorA !== null && games[i].goalsPenaltyParticipatorB !== null) {
                            item.find('.result .goals-a').text(games[i].goalsParticipatorA + ' (' + games[i].goalsPenaltyParticipatorA + ')');
                            item.find('.result .goals-b').text(games[i].goalsParticipatorB + ' (' + games[i].goalsPenaltyParticipatorB + ')');
                        } else {
                            item.find('.result .goals-a').text(games[i].goalsParticipatorA);
                            item.find('.result .goals-b').text(games[i].goalsParticipatorB);
                        }

//                        item.find('.tv').addClass('hidden');
//                        item.find('.date').addClass('hidden');
//                        item.find('.time').addClass('hidden');
//                        item.find('.result-header').removeClass('hidden');
//                        item.find('.result').removeClass('hidden');
//                        item.find('.location').addClass('hidden');
                    } else {
                        if (games[i].tv) {
                            item.find('.tv').attr('src', TV_IMAGE_PATH + games[i].tv + '.png');
                        } else {
//                            item.find('.tv').addClass('hidden');
                        }
                    }

                    if (games[i].participatorA !== null && games[i].participatorB !== null) {
                        var countryA = getCountryByIso(games[i].participatorA);
                        if (countryA) {
                            item.find('.participator-a .country').text(countryA.name);
                            item.find('.participator-a .flag').attr('src', FLAG_IMAGE_PATH + countryA.iso + '.png');
                        }
                        var countryB = getCountryByIso(games[i].participatorB);
                        if (countryB) {
                            item.find('.participator-b .country').text(countryB.name);
                            item.find('.participator-b .flag').attr('src', FLAG_IMAGE_PATH + countryB.iso + '.png');
                        }

                        if (games[i].goalsParticipatorA !== null && games[i].goalsParticipatorB !== null) {
                            var startGame = new Date(games[i].timestamp * 1000);
                            var now = new Date();
                            var timeDiff = Math.abs(startGame.getTime() - now.getTime());
                            var diffHours = Math.ceil(timeDiff / (1000 * 3600));
                            if (diffHours > 12) {
//                                $(item).find('.done').removeClass('hidden');
//                                $(item).find('.actual').addClass('hidden');
//                                $(item).find('.progress').addClass('hidden');
//                                $(item).find('.user-tip').addClass('hidden');
                            }
                        }
                    } else {
//                        tipSubmitImpossible(item);
                        item.find('.participator-a .flag').attr('src', FLAG_IMAGE_PATH + 'xx.png');
                        item.find('.participator-b .flag').attr('src', FLAG_IMAGE_PATH + 'xx.png');
                    }

                    $(item).unbind('click').bind('click', function (event) {
                        event.preventDefault();
                        currentEditGameId = $(this).attr('data-game-id');
                        renderCountries();
                        initStepperFunctionalities($('#editGameModal'));
                        initDropDownSelection();
                        var game = getGameById(currentEditGameId);
//                        console.log('edit game', game);
                        var modal = $('#editGameModal');
                        $(modal).find('#btn-delete-game').removeClass('hidden');
                        $(modal).modal('show');


                        if (game) {
                            if (game.timestamp) {
                                $(modal).find('#date-picker').datetimepicker({
                                    inline: true,
                                    sideBySide: true,
                                    defaultDate: game.timestamp ? new Date(game.timestamp * 1000) : null,
                                    locale: 'de'
                                });
                            }

                            if (game.location) {
                                $(modal).find('#select-location #' + getLocationIdByTitle(game.location)).click();
                            }

                            if (game.round) {
                                $(modal).find('#select-round #' + game.round).click();
                            }

                            if (game.participatorA) {
                                $(modal).find('#select-participator-a #' + game.participatorA).click();
                            }

                            if (game.participatorB) {
                                $(modal).find('#select-participator-b #' + game.participatorB).click();
                            }

                            if (game.goalsParticipatorA !== null) {
                                $(modal).find('#goals-participator-a .stepper-text').val(game.goalsParticipatorA);
                            }

                            if (game.goalsParticipatorB !== null) {
                                $(modal).find('#goals-participator-b .stepper-text').val(game.goalsParticipatorB);
                            }

                            if (game.goalsPenaltyParticipatorA !== null) {
                                $(modal).find('#penalty-goals-participator-a .stepper-text').val(game.goalsPenaltyParticipatorA);
                            }

                            if (game.goalsPenaltyParticipatorB !== null) {
                                $(modal).find('#penalty-goals-participator-b .stepper-text').val(game.goalsPenaltyParticipatorB);
                            }
                        }
                    });
                }
            }

            function getLocationIdByTitle(title) {
                for (var i = 0; i < LOCATIONS.length; i++) {
                    if (LOCATIONS[i].title === title) {
                        return LOCATIONS[i].id;
                    }
                }
                return null;
            }

            function getLocationById(id) {
                for (var i = 0; i < LOCATIONS.length; i++) {
                    if (LOCATIONS[i].id === id) {
                        return LOCATIONS[i];
                    }
                }
                return null;
            }

            function initStepperFunctionalities(item) {
                console.log(item, $(item).find('.simple-stepper .btn-stepper-increase'));
                /*
                 * simple stepper functions
                 */
                $(item).find('.simple-stepper .btn-stepper-decrease').unbind('click').bind('click', function (event) {
                    event.preventDefault();
                    if (event.handled !== true)
                    {
                        event.handled = true;
                        var min = parseInt($(this).val());
                        var currentValue = parseInt($(this).closest('.simple-stepper').find('.stepper-text').val());
                        if (currentValue === "" || isNaN(currentValue)) {
                            currentValue = min;
                        } else if (currentValue > min) {
                            currentValue--;
                        } else {
                            currentValue = min;
                        }
                        $(this).closest('.simple-stepper').find('.stepper-text').val(currentValue);
                        $(this).closest('.simple-stepper').find('.stepper-text').trigger('change');
                    }
                });

                $(item).find('.simple-stepper .btn-stepper-increase').unbind('click').bind('click', '.simple-stepper .btn-stepper-increase', function (event) {
                    event.preventDefault();
                    if (event.handled !== true)
                    {
                        event.handled = true;
                        var max = parseInt($(this).val());
                        var min = parseInt($(this).closest('.simple-stepper').find('.btn-stepper-decrease').val());
                        var currentValue = parseInt($(this).closest('.simple-stepper').find('.stepper-text').val());

                        if (currentValue === "" || isNaN(currentValue)) {
                            currentValue = min;
                        } else if (currentValue < max) {
                            currentValue++;
                        } else {
                            currentValue = max;
                        }
                        $(this).closest('.simple-stepper').find('.stepper-text').val(currentValue);
                        $(this).closest('.simple-stepper').find('.stepper-text').trigger('change');
                    }
                });

//                $(item).find('.simple-stepper .btn-stepper-increase').unbind('mousedown').bind('mousedown', function (event) {
//                    event.preventDefault();
//                    event.stopPropagation();
//                    console.log('increase');
//                    var button = $(this);
//
//                    mouseDownInterval = setInterval(function () {
//                        mouseHoldInterval = 50;
//                        button.click();
//                        clearInterval(mouseDownInterval);
//                        mouseDownInterval = setInterval(function () {
//                            button.click();
//                        }, mouseHoldInterval);
//                    }, mouseHoldInterval);
//                });

//                $(item).find('.simple-stepper .btn-stepper-increase').unbind('mouseup').bind('mouseup', function (event) {
//                    event.preventDefault();
//                    event.stopPropagation();
//                    clearInterval(mouseDownInterval);
//                    mouseHoldInterval = 800;
//                });

//                $(item).find('.simple-stepper .btn-stepper-decrease').unbind('mousedown').bind('mousedown', function (event) {
//                    event.preventDefault();
//                    event.stopPropagation();
//                    var button = $(this);
//
//                    mouseDownInterval = setInterval(function () {
//                        mouseHoldInterval = 50;
//                        button.click();
//                        clearInterval(mouseDownInterval);
//                        mouseDownInterval = setInterval(function () {
//                            button.click();
//                        }, mouseHoldInterval);
//                    }, mouseHoldInterval);
//                });

//                $(item).find('.simple-stepper .btn-stepper-increase').unbind('mouseup').bind('mouseup', '.simple-stepper .btn-stepper-decrease', function (event) {
//                    event.preventDefault();
//                    event.stopPropagation();
//                    clearInterval(mouseDownInterval);
//                    mouseHoldInterval = 800;
//                });
            }
            var mouseDownInterval;
            var mouseDownTimer;
            var mouseHoldInterval = 800;

            function renderCountries() {
                var countries = getLocalItem(COUNTRIES);
//                console.log('render countries', countries);

                var dropdown = $('#editGameModal').find('#select-participator-a, #select-participator-b');
//                console.log(dropdown);
                if (countries && countries.length > 0)
                {
                    countries = sortByKey(countries, 'name');
                    $(dropdown).find('.option').empty();
                    $(dropdown).find('.dropdown-toggle').removeClass('disabled');

//                    $(dropdown).parent().find('.show-dropdown').unbind('click').bind('click', function (event) {
//                        event.preventDefault();
//                        event.stopPropagation();
//                        onShowDropdownClicked($(this));
//                    });

//                    $(dropdown).parent().find('.show-dropdown').unbind('change').bind('change', function (event) {
//                        event.preventDefault();
//                        renderPlaySessions($(this).parent().find('.chosen').attr('id'));
//                    });

                    var listItem;
                    for (var i = 0; i < countries.length; i++) {
                        listItem = document.createElement('li');
                        listItem.setAttribute('id', countries[i].iso);

                        $(listItem).unbind('click').bind('click', function (event) {
                            event.preventDefault();
//                            onListItemClicked($(this));
                            $(this).closest('.input-group').find('.item-input-text').change();
                        });

                        var link = document.createElement('a');
                        link.setAttribute('href', '#');
                        link.appendChild(document.createTextNode(countries[i].name));
                        listItem.appendChild(link);
                        $(dropdown).find('.option').append(listItem);
                    }
                    $(dropdown).find('.option-trigger').attr('placeholder', 'Bitte wählen');
                }
            }

            function initDropDownSelection() {
                $('#editGameModal').find('.select .option li').on('click', function (event) {
                    event.preventDefault();

                    if (!event.handled && !$(this).hasClass('disabled')) {
//                        console.log('clicked');
                        event.handled = true;
                        if ($(this).hasClass('dropdown-header') || $(this).hasClass('divider') || $(this).hasClass('selected')) {
                            return false;
                        }

                        var parent = $(this).closest('.select');
                        var itemText = $(this).children().text();
                        var listItemId = $(this).attr('id');
                        $(parent).find('.chosen').attr('id', listItemId);
                        $(parent).prev().val(itemText);
                        $(this).parent().children('li').removeClass('selected');
                        $(this).addClass('selected');
                        var disabledElements = $(parent).children('.dropdown-disabled');
                        if (disabledElements.length > 0) {
                            for (var i = 0; i < disabledElements.length; i++) {
                                $(disabledElements[i]).removeClass('disabled');
                            }
                        }

                        $(this).closest('.input-group').find('.item-input-text').attr('placeholder', '');

                        if (parent.hasClass('saveGeneralData')) {
                            saveGeneralData();
                        }

//                    $(this).trigger('change', [listItemId]);
                    }
                });
            }

            $('#btn-save-game').on('click', function (event) {
                var modal = $('#editGameModal');
//                var date = $(modal).find('#date-picker').data("DateTimePicker").date();
                var dateInput = $(modal).find('#date-picker').data("DateTimePicker").viewDate();
                var date = new Date(new Date(dateInput._d));
//                var momentInput = dateInput._d;

//                date = moment(momentInput).format('YYYY-MM-DD HH:mm:ss');
//                console.log(momentInput);


                date.setSeconds(0, 0);
                date = date.getTime() / 1000;
//                date.setSeconds(0, 0);
//                date = date.getTime() * 1000;
//                date = date.toUTCString().slice(0, 19).replace('T', ' ');
//                date.toISOString()
//                date = date.toISOString().slice(0, 19).replace('T', ' ');

                var location = getLocationById($(modal).find('#select-location .chosen').attr('id')).title;
                var round = $(modal).find('#select-round .chosen').attr('id');
                var participatorA = $(modal).find('#select-participator-a .chosen').attr('id');
                var participatorB = $(modal).find('#select-participator-b .chosen').attr('id');
                var goalsParticipatorA = $(modal).find('#goals-participator-a .stepper-text').val();
                var goalsParticipatorB = $(modal).find('#goals-participator-b .stepper-text').val();
                var goalsPenaltyParticipatorAInput = $(modal).find('#penalty-goals-participator-a .stepper-text').val();
                var goalsPenaltyParticipatorA = parseInt(goalsPenaltyParticipatorAInput) === 0 ? null : goalsPenaltyParticipatorAInput;
                var goalsPenaltyParticipatorBInput = $(modal).find('#penalty-goals-participator-b .stepper-text').val();
                var goalsPenaltyParticipatorB = parseInt(goalsPenaltyParticipatorBInput) === 0 ? null : goalsPenaltyParticipatorBInput;
                var updateGoals = $(modal).find('#select-update-goals .btn-option-checked').attr('id');

//                console.log(currentEditGameId, date, location, round, participatorA, participatorB, updateGoals, goalsParticipatorA, goalsParticipatorB, goalsPenaltyParticipatorA, goalsPenaltyParticipatorB);

                // check inputs
                if (location === 'unselected') {
//                    console.log('location === unselected');
                    return false;
                }

                if (round === 'unselected') {
//                    console.log('round === unselected');
                    return false;
                }

                if (participatorA === 'unselected') {
//                    console.log('participator A === unselected');
                    return false;
                }

                if (participatorB === 'unselected') {
//                    console.log('participator B === unselected');
                    return false;
                }

                var button = $(this);
                lockButton(button, true, 'fa-save');

                if (currentEditGameId !== null) {
                    // update game
//                    console.log('update game: ', currentEditGameId);
                    updateGame({id: currentEditGameId, date: date, location: location, participatorA: participatorA, participatorB: participatorB, updateGoals: updateGoals, goalsParticipatorA: goalsParticipatorA, goalsParticipatorB: goalsParticipatorB, goalsPenaltyParticipatorA: goalsPenaltyParticipatorA, goalsPenaltyParticipatorB: goalsPenaltyParticipatorB, round: round}, function (result) {
                        unlockButton(button, true, 'fa-save');
                        if (result.status === RESULT_SUCCESS) {
//                            console.log('game updated');
                            renderData();
                            $('#editGameModal').modal('hide');
                        }
                    });
                } else {
                    // create new game
//                    console.log('create new game');
                    createGame({date: date, location: location, participatorA: participatorA, participatorB: participatorB, updateGoals: updateGoals, goalsParticipatorA: goalsParticipatorA, goalsParticipatorB: goalsParticipatorB, goalsPenaltyParticipatorA: goalsPenaltyParticipatorA, goalsPenaltyParticipatorB: goalsPenaltyParticipatorB, round: round}, function (result) {
                        unlockButton(button, true, 'fa-save');
                        if (result.status === RESULT_SUCCESS) {
//                            console.log('game created');
                            $('#editGameModal').modal('hide');
                            renderData();
                        }
                    });
                }
            });

//            $('#btn-close-edit-game-modal').on('click', function (event) {
//                event.preventDefault();
//                $('#editGameModal').modal('hide');
//            });

            $('#btn-add-game').on('click', function (event) {
                event.preventDefault();
                currentEditGameId = null;

                renderCountries();
                initStepperFunctionalities($('#editGameModal'));
                initDropDownSelection();
                var modal = $('#editGameModal');
                $(modal).find('#btn-delete-game').addClass('hidden');

                $(modal).find('#date-picker').datetimepicker({
                    inline: true,
                    sideBySide: true,
//                    defaultDate: game.timestamp ? new Date(game.timestamp * 1000) : null,
                    locale: 'de'
                });


                $(modal).modal('show');
            });

            $('#btn-delete-game').on('click', function (event) {
                event.preventDefault();
                var button = $(this);

                $(button).addClass('hidden');
                $('#check-delete-game').removeClass('hidden');
            });

            $('#btn-cancel-delete-game').on('click', function (event) {
                event.preventDefault();
                $('#btn-delte-game').removeClass('hidden');
                $('#check-delete-game').addClass('hidden');
            });

            $('#btn-submit-delete-game').on('click', function (event) {
                event.preventDefault();
                var button = $(this);
                lockButton(button, true, 'fa-trash');

                deleteGame({id: currentEditGameId}, function (result) {
                    unlockButton(button, true, 'fa-trash');
                    if (result.status === RESULT_SUCCESS) {
                        renderData();
                        $('#editGameModal').modal('hide');
                    }
                });
            });

        </script>

    </body>
</html>