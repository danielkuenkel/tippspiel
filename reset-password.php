<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
$h = getv('h');

if (!$h) {
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Tippspiel - Passwort zurücksetzen</title>
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

        <script type="text/JavaScript" src="js/contants.js"></script> 
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <script type="text/JavaScript" src="js/storage.js"></script>
        <script type="text/JavaScript" src="js/alert.js"></script>
        <script type="text/JavaScript" src="js/functions.js"></script>
    </head>
    <body id="pageBody" data-spy="scroll" data-target=".navbar" data-offset="60">

        <!-- alerts -->
        <div id="alert-container" class="hidden">
            <div class="alert alert-danger" id="general-error" role="alert"><i class="glyphicon glyphicon-alert"></i> Es ist ein unbekannter Fehler aufgetreten!</div>
            <div class="alert alert-danger" id="reset-password-error" role="alert"><i class="glyphicon glyphicon-alert"></i> Es ist ein Fehler beim Passwort-Reset aufgetreten. Bitte überprüfen Sie ihre Eingaben.</div>
            <div class="alert alert-success" id="password-reset-success" role="alert"><i class="glyphicon glyphicon-alert"></i> Das neue Passwort wurde erfolgreich gespeichert. Sie können sich nun einloggen.</div>
            <div class="alert alert-warning" id="missing-fields" role="alert"><i class="glyphicon glyphicon-alert"></i> Bitte alle Felder ausfüllen!</div>
            <div class="alert alert-warning" id="invalid-email" role="alert"><i class="glyphicon glyphicon-alert"></i> E-Mail-Adresse entspricht nicht dem Standard!</div>
            <div class="alert alert-warning" id="check-email" role="alert"><i class="glyphicon glyphicon-alert"></i> Bitte überprüfen Sie die eingegebene E-Mail-Adresse!</div>
            <div class="alert alert-warning" id="password-short" role="alert"><i class="glyphicon glyphicon-alert"></i> Das eingegebene Passwort muss mindestens 6 Zeichen haben!</div>
            <div class="alert alert-warning" id="password-invalid" role="alert"><i class="glyphicon glyphicon-alert"></i> Das eingegebene Passwort muss mindestens eine Zahl, einen großen und einen kleinen Buchstaben enthalten!</div>
            <div class="alert alert-warning" id="passwords-not-matching" role="alert"><i class="glyphicon glyphicon-alert"></i> Die eingegebenen Passwörter stimmen nicht überein! Bitte überprüfe die Passwörter noch einmal.</div>
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



        <div class="container main-content" id="main-content">

            <!--<div class="alert alert-success" role="alert"><i class="fa fa-futbol-o" aria-hidden="true"></i> <strong>LA GRANDE FINALE</strong> Danke für eure rege Teilnahme. <strong>GLÜCKWUNSCH dem Gewinner</strong>. Ich hoffe wir können bei der WM in 2 Jahren mehr Leute animieren teilzunehmen und wünsche mir natürlich wieder so eine tolle Tippspielrunde. Natürlich nur wenn ihr auch wollt. Für Kritik, Wünsche, Anregungen und Lob könnt ihr mir natürlich <a href="mailto:danielkuenkel@googlemail.com?subject=Tippspiel">gerne schreiben</a>. Die Daten werden dann zeitnah von mir gelöscht. Tschüss und bis zum nächsten Mal. Euer Daniel.</div>-->
            <div class="alert-space alert-general-error"></div>



            <div  id="password-reset">
                <h2 class="panel-title"><?php echo $lang->updatePassword ?></h2>

                <div id="reset-password-form">
                    <div class="alert-space alert-general-error"></div>
                    <div class="alert-space alert-missing-fields"></div>
                    <div class="alert-space alert-reset-password-error"></div>

                    <div id="form-groups">

                        <div class="form-group">
                            <label for="email">E-Mail-Adresse</label>
                            <div class="alert-space alert-invalid-email"></div>
                            <div class="alert-space alert-check-email"></div>
                            <input type="email" class="form-control" name="email" id="email" placeholder="">
                        </div>

                        <div class="form-group">
                            <label for="password">Neues Passwort</label>
                            <div class="alert-space alert-password-short"></div>
                            <div class="alert-space alert-password-invalid"></div>

                            <input type="password" class="form-control" name="password" id="password" placeholder="">
                        </div>
                        <div class="form-group">
                            <label for="confirmPassword">Neues Passwort wiederholen</label>
                            <div class="alert-space alert-passwords-not-matching"></div>
                            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="">
                        </div>

                    </div>

                </div>

                <button type="button" class="btn btn-default btn-shadow pull-right" id="btn-reset-password"><i class="fa fa-pencil" aria-hidden="true"></i> <span class="btn-text">Passwort zurücksetzen</span></button>
                <div class="clearfix"></div>

            </div>

            <div id="goto-login" class="hidden">
                <div class="alert-space alert-password-reset-success"></div>
                <button type="button" class="btn btn-success btn-shadow btn-block" id="btn-goto-login"><span class="btn-text">Zur Startseite</span></button>
            </div>


        </div>


        <script>
            $(document).ready(function () {
                $('#btn-reset-password').on('click', function (event) {
                    event.preventDefault();
                    if (!$(this).hasClass('disabled')) {
                        clearAlerts($('#reset-password-form'));
                        resetPasswordFormhash($('#reset-password-form'));
                    }
                });

                $(document).on('submit', '#reset-password-form', function (event) {
                    event.preventDefault();
                    var formElement = $(this);
                    clearAlerts(formElement);
                    var button = $(this);
                    lockButton($(button), true, 'fa-pencil');

                    var query = getQueryParams(document.location.search);
                    if (query && query.h) {
                        var data = {email: $(formElement).find('#email').val().trim(), p: $(formElement).find('#p').val(), hash: query.h};
                        resetPassword(data, function (result) {
                            unlockButton($(button), true, 'fa-pencil');
                            if (result.status === RESULT_SUCCESS) {
                                appendAlert($('#goto-login'), ALERT_PASSWORD_RESET_SUCCESS);
                                $('#password-reset').addClass('hidden');
                                $('#goto-login').removeClass('hidden');
                            } else if (result.status === 'resetPasswordError') {
                                appendAlert($('#reset-password-form'), ALERT_RESET_PASSWORD_ERROR);
                            } else if (result.status === 'checkEmail') {
                                appendAlert($('#reset-password-form'), ALERT_CHECK_EMAIL);
                            } else {
                                appendAlert($('#reset-password-form'), ALERT_GENERAL_ERROR);
                            }
                        });
                    }
                });
            });

            $('#btn-goto-login').on('click', function (event) {
                goto('index.php');
            });
        </script>

    </body>
</html>