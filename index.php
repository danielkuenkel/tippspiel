<?php
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
session_start();


$checkLogin = login_check($mysqli);
if ($checkLogin === 'not-payed-yet') {
    
} else if (login_check($mysqli) == true) {
    header('Location: dashboard.php');
} else if ($checkLogin == false) {
//    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Tippspiel WM 2018</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="-1">
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE">

        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <link rel="icon" href="favicon.ico" type="image/x-icon">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/general.css">
        <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">

        <script src="js/jquery/jquery-3.3.1.min.js"></script>

        <!-- Latest compiled and minified JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/randomcolor/0.4.4/randomColor.js"></script>
        <script src="js/chance.min.js"></script>

        <script type="text/JavaScript" src="js/contants.js"></script> 
        <script type="text/JavaScript" src="js/sha512.js"></script> 
        <script type="text/JavaScript" src="js/forms.js"></script>
        <script type="text/JavaScript" src="js/storage.js"></script>
        <script type="text/JavaScript" src="js/alert.js"></script>
        <script type="text/JavaScript" src="js/functions.js"></script>

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
            <div class="alert alert-warning" id="missing-fields" role="alert"><i class="glyphicon glyphicon-alert"></i> Bitte alle Felder ausfüllen!</div>
            <div class="alert alert-warning" id="missing-email" role="alert"><i class="glyphicon glyphicon-alert"></i> Bitte tragen Sie eine E-Mail-Adresse ein!</div>
            <div class="alert alert-warning" id="login-failed" role="alert"><i class="glyphicon glyphicon-alert"></i> Einloggen fehlgeschlagen. Bitte versuche es erneut!</div>
            <div class="alert alert-danger" id="account-logged" role="alert"><i class="glyphicon glyphicon-alert"></i> Du hast zu oft versucht dich einzuloggen! Dein Account ist deshalb die nächsten 2 Stunden gesperrt!</div>
            <div class="alert alert-warning" id="wrong-password" role="alert"><i class="glyphicon glyphicon-alert"></i> Das eingegebene Passwort ist falsch!</div>
            <div class="alert alert-warning" id="check-email" role="alert"><i class="glyphicon glyphicon-alert"></i> Bitte überprüfen Sie die eingegebene E-Mail-Adresse!</div>
            <div class="alert alert-success" id="password-reset-send" role="alert"><i class="glyphicon glyphicon-alert"></i> Es wurde eine E-Mail an Ihre eingegebene E-Mail-Adresse mit einem Passwort-Reset gesendet. Überprüfen Sie auch Ihr Spam-Postfach.</div>
            <div class="alert alert-warning" id="password-reset-success" role="alert"><i class="glyphicon glyphicon-alert"></i> Das neue Passwort wurde erfolgreich gespeichert. Sie können sich nun einloggen.</div>
            <div class="alert alert-warning" id="reset-password-error" role="alert"><i class="glyphicon glyphicon-alert"></i> Es ist ein Fehler beim Passwort-Reset aufgetreten. Bitte überprüfen Sie ihre Eingaben.</div>
            <div class="alert alert-warning" id="password-not-correct" role="alert"><i class="glyphicon glyphicon-alert"></i> Das neue Passwort wurde erfolgreich gespeichert. Sie können sich nun einloggen.</div>
            <div class="alert alert-warning" id="invalid-email" role="alert"><i class="glyphicon glyphicon-alert"></i> E-Mail-Adresse entspricht nicht dem Standard!</div>
            <div class="alert alert-warning" id="password-short" role="alert"><i class="glyphicon glyphicon-alert"></i> Das eingegebene Passwort muss mindestens 6 Zeichen haben!</div>
            <div class="alert alert-warning" id="password-invalid" role="alert"><i class="glyphicon glyphicon-alert"></i> Das eingegebene Passwort muss mindestens eine Zahl, einen großen und einen kleinen Buchstaben enthalten!</div>
            <div class="alert alert-warning" id="user-exists" role="alert"><i class="glyphicon glyphicon-alert"></i> Der eingegebene Benutzer mit dieser E-Mail-Adresse existiert bereits!</div>
            <div class="alert alert-warning" id="no-user-exists" role="alert"><i class="glyphicon glyphicon-alert"></i> Der eingegebene Benutzer mit dieser E-Mail-Adresse existiert nicht!</div>
            <div class="alert alert-warning" id="passwords-not-matching" role="alert"><i class="glyphicon glyphicon-alert"></i> Die eingegebenen Passwörter stimmen nicht überein! Bitte überprüfe die Passwörter noch einmal.</div>
            <div class="alert alert-success" id="register-success" role="alert"><i class="glyphicon glyphicon-ok"></i> Du hast dich erfolgreich registriert. Du kannst dich nun einloggen!</div>
        </div>

        <!-- imprint modal -->
        <div id="moreInfosModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg root">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Impressum</h4>
                    </div>
                    <div class="modal-body">
                        <div class="info">
                            <div class="page-header">
                                <h2>Angaben gemäß § 5 TMG:</h2>
                            </div>
                            <div class="info-text">
                                <p>Daniel Künkel<br/>
                                    Petersberger Straße 50<br/>
                                    36037 Fulda</p>
                                <p>E-Mail: <a href="mailto:danielkuenkel@googlemail.com"><i class="glyphicon glyphicon-link"></i> danielkuenkel@googlemail.com</a></p>
                                <p>Quelle: <a href="https://www.e-recht24.de"><i class="glyphicon glyphicon-link"></i> e-recht24.de</a></p>
                            </div>
                        </div>
                        <div class="info">
                            <div class="page-header">
                                <h2>Haftungsausschluss (Disclaimer)</h2>
                            </div>
                            <div class="info-text">
                                <p><strong>Haftung für Inhalte</strong></p>
                                <p>Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu forschen, die auf eine rechtswidrige Tätigkeit hinweisen. Verpflichtungen zur Entfernung oder Sperrung der Nutzung von Informationen nach den allgemeinen Gesetzen bleiben hiervon unberührt. Eine diesbezügliche Haftung ist jedoch erst ab dem Zeitpunkt der Kenntnis einer konkreten Rechtsverletzung möglich. Bei Bekanntwerden von entsprechenden Rechtsverletzungen werden wir diese Inhalte umgehend entfernen.</p>
                                <p><strong>Haftung für Links</strong></p>
                                <p>Unser Angebot enthält Links zu externen Webseiten Dritter, auf deren Inhalte wir keinen Einfluss haben. Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich. Die verlinkten Seiten wurden zum Zeitpunkt der Verlinkung auf mögliche Rechtsverstöße überprüft. Rechtswidrige Inhalte waren zum Zeitpunkt der Verlinkung nicht erkennbar. Eine permanente inhaltliche Kontrolle der verlinkten Seiten ist jedoch ohne konkrete Anhaltspunkte einer Rechtsverletzung nicht zumutbar. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Links umgehend entfernen.</p>
                                <p><strong>Urheberrecht</strong></p>
                                <p>Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers. Downloads und Kopien dieser Seite sind nur für den privaten, nicht kommerziellen Gebrauch gestattet. Soweit die Inhalte auf dieser Seite nicht vom Betreiber erstellt wurden, werden die Urheberrechte Dritter beachtet. Insbesondere werden Inhalte Dritter als solche gekennzeichnet.</p>
                                <p>Sollten Sie trotzdem auf eine Urheberrechtsverletzung aufmerksam werden, bitten wir um einen entsprechenden Hinweis. Bei Bekanntwerden von Rechtsverletzungen werden wir derartige Inhalte umgehend entfernen.</p>

                            </div>
                        </div>

                        <div class="info">
                            <div class="page-header">
                                <h2>Datenschutzerklärung</h2>
                            </div>
                            <div class="info-text">
                                <p><strong>Datenschutz</strong></p>
                                <p>Die Betreiber dieser Seiten nehmen den Schutz Ihrer persönlichen Daten sehr ernst. Wir behandeln Ihre personenbezogenen Daten vertraulich und entsprechend der gesetzlichen Datenschutzvorschriften sowie dieser Datenschutzerklärung.</p>
                                <p>Die Nutzung unserer Webseite ist in der Regel ohne Angabe personenbezogener Daten möglich. Soweit auf unseren Seiten personenbezogene Daten (beispielsweise Name, Anschrift oder E-Mail-Adressen) erhoben werden, erfolgt dies, soweit möglich, stets auf freiwilliger Basis. Diese Daten werden ohne Ihre ausdrückliche Zustimmung nicht an Dritte weitergegeben.</p>
                                <p>Wir weisen darauf hin, dass die Datenübertragung im Internet (z.B. bei der Kommunikation per E-Mail) Sicherheitslücken aufweisen kann. Ein lückenloser Schutz der Daten vor dem Zugriff durch Dritte ist nicht möglich.</p>

                                <p><strong>Cookies</strong></p>
                                <p>Die Internetseiten verwenden teilweise so genannte Cookies. Cookies richten auf Ihrem Rechner keinen Schaden an und enthalten keine Viren. Cookies dienen dazu, unser Angebot nutzerfreundlicher, effektiver und sicherer zu machen. Cookies sind kleine Textdateien, die auf Ihrem Rechner abgelegt werden und die Ihr Browser speichert.</p>
                                <p>Die meisten der von uns verwendeten Cookies sind so genannte „Session-Cookies“. Sie werden nach Ende Ihres Besuchs automatisch gelöscht. Andere Cookies bleiben auf Ihrem Endgerät gespeichert, bis Sie diese löschen. Diese Cookies ermöglichen es uns, Ihren Browser beim nächsten Besuch wiederzuerkennen.</p>
                                <p>Sie können Ihren Browser so einstellen, dass Sie über das Setzen von Cookies informiert werden und Cookies nur im Einzelfall erlauben, die Annahme von Cookies für bestimmte Fälle oder generell ausschließen sowie das automatische Löschen der Cookies beim Schließen des Browser aktivieren. Bei der Deaktivierung von Cookies kann die Funktionalität dieser Website eingeschränkt sein.</p>

                                <p><strong>Server-Log-Files</strong></p>
                                <p>Der Provider der Seiten erhebt und speichert automatisch Informationen in so genannten Server-Log Files, die Ihr Browser automatisch an uns übermittelt. Dies sind:</p>
                                <p><ul>
                                    <li>Browsertyp/ Browserversion</li>
                                    <li>verwendetes Betriebssystem</li>
                                    <li>Referrer URL</li>
                                    <li>Hostname des zugreifenden Rechners</li>
                                    <li>Uhrzeit der Serveranfrage.</li>
                                </ul>
                                </p>
                                <p>Diese Daten sind nicht bestimmten Personen zuordenbar. Eine Zusammenführung dieser Daten mit anderen Datenquellen wird nicht vorgenommen. Wir behalten uns vor, diese Daten nachträglich zu prüfen, wenn uns konkrete Anhaltspunkte für eine rechtswidrige Nutzung bekannt werden.</p>

                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Schließen</button>
                    </div>
                </div>

            </div>
        </div>

        <!-- register modal -->
        <div id="registerModal" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg root">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Benutzerkonto anlegen</h4>
                    </div>
                    <div class="modal-body">

                        <div id="register-form">
                            <div class="alert-space alert-general-error"></div>
                            <div class="alert-space alert-missing-fields"></div>
                            <div class="alert-space alert-register-success"></div>

                            <div id="form-groups">
                                <div class="form-group">
                                    <label for="email">E-Mail-Adresse</label>
                                    <div class="alert-space alert-user-exists"></div>
                                    <div class="alert-space alert-invalid-email"></div>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="username">Benutzername</label>
                                    <input type="text" class="form-control" name="forename" id="username" placeholder="">
                                </div>

                                <div class="form-group">
                                    <label for="password">Passwort</label>
                                    <div class="alert-space alert-password-short"></div>
                                    <div class="alert-space alert-password-invalid"></div>
                                    <input type="password" class="form-control" name="password" id="password" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword">Passwort wiederholen</label>
                                    <div class="alert-space alert-passwords-not-matching"></div>
                                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="">
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-block btn-success hidden" id="btn-close" data-dismiss="modal" aria-label="Close"><i class="fa fa-close"></i> <span class="btn-text">Schließen</span></button>
                        <button type="button" class="btn btn-block btn-default" id="btn-register"><i class="fa fa-user-plus" aria-hidden="true"></i> <span class="btn-text">Registrieren</span></button>
                    </div>
                </div>

            </div>
        </div>


        <!-- header content -->
        <div class="jumbotron text-center">
            <div class="container">
                <h1><i class="fa fa-soccer-ball-o"></i> KICK TIPP</h1>
                <p>Tippe und messe dich!</p>
            </div>
        </div>
        <div class="line">
            <div class="line-white"></div>
            <div class="line-blue"></div>
            <div class="line-red"></div>
        </div>


        <!-- main content -->
        <div class="container main-content" id="main-content" style="margin-top: 30px;">

            <div class="row">

                <div class="col-sm-6 col-sm-push-6 col-md-6" style="margin-top: 40px;">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Einloggen & Registrieren</h3>
                        </div>
                        <div class="panel-body">

                            <form role="form" id="login-form">
                                <div class="alert-space alert-general-error"></div>
                                <div class="alert-space alert-missing-fields"></div>

                                <div class="alert-space alert-login-failed"></div>
                                <div class="alert-space alert-account-logged"></div>

                                <div class="alert-space alert-check-password"></div>
                                <div class="alert-space alert-password-reset-send"></div>

                                <div class="form-group">
                                    <label for="email">E-Mail-Adresse:</label>
                                    <div class="alert-space alert-no-user-exists"></div>
                                    <div class="alert-space alert-check-email"></div>
                                    <div class="alert-space alert-missing-email"></div>
                                    <input type="text" class="form-control" name="email" value="" id="email">
                                </div>
                                <div class="form-group">
                                    <label for="password">Password:</label>
                                    <div class="alert-space alert-wrong-password"></div>
                                    <input type="password" class="form-control" name="password" value="" id="password">
                                </div>
                                <div class="btn-group-vertical btn-block">
                                    <button type="button" class="btn btn-success btn-shadow" id="btn-login"><i class="fa fa-unlock-alt"></i> <span class="btn-text">Einloggen</button>
                                    <button type="button" class="btn btn-default btn-shadow" id="btn-forgot-password"><i class="fa fa-question"></i> <span class="btn-text">Passwort vergessen</button>
                                    <button type="button" class="btn btn-default btn-shadow" id="btn-open-register"><i class="fa fa-user-plus" aria-hidden="true"></i> <span class="btn-text">Registrieren</span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-sm-pull-6 col-md-6">
                    <div class="info">
                        <div class="page-header">
                            <h2><i class="glyphicon glyphicon-question-sign"></i> Was ist KICK TIPP?</h2>
                        </div>
                        <div class="info-text">
                            <p>Aus einer Idee wurde eine kleine Machbarkeitsstudie. Daraus entwickelte sich dann aber in kurzer Zeit dieses Tippspiel. Es ist für Dich! Hier kannst du begleitend zur Fussball-WM 2018 deine Tipps auf einzelne Partien abgeben. Je besser du tippst, desto mehr Punkte erhälst du, desto weiter oben landest du auf der Rangliste.</p>
                            <p><strong>Also registriere dich einfach und mache mit!</strong> Viel Spaß!</p>

                            <div class="embed-responsive embed-responsive-16by9" style="border-radius: 5px">
                                <iframe class="embed-responsive-item" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"width="788.54" height="443" type="text/html" src="https://www.youtube.com/embed/iXKGewfAc1U?autoplay=0&fs=0&iv_load_policy=3&showinfo=0&rel=0&cc_load_policy=0&start=0&end=0&origin=https://youtubeembedcode.com"><div><small><a href="https://youtubeembedcode.com/de/">Discover More</a></small></div><div><small><a href="https://zorgverzekeringvergelijkenstudenten.nl/">zorgverzekeringvergelijkenstudenten</a></small></div></iframe>
                            </div>
                            <p style="margin-top: 10px"><strong>INFO!</strong> Da es sich um ein privates Projekt handelt, kann keine 100%-ige Funtkionstüchtigkeit gewährleistet werden. Falls ein Fehler auftritt, lade als erstes die Seite neu. Wenn dies das Problem nicht löst oder es andere Probleme gibt, dann schreibe mir einfach!</p>

                        </div>
                    </div>

                </div>

                <div class="col-sm-12">

                    <div class="info">
                        <div class="page-header">
                            <h2><i class="fa fa-users"></i> Die Mannschaft</h2>
                        </div>
                        <div class="info-text">
                            <h3 class="text-center">TOR</h3>
                            <div class="row">
                                <div class="col-sm-12 col-md-4 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/neuer.jpg"/>
                                        <div class="player-number">1</div>
                                    </div>
                                    Manuel <b>NEUER</b>
                                </div>
                                <div class="col-sm-6 col-md-4 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/trapp.jpg"/>
                                        <div class="player-number">12</div>
                                    </div>
                                    Kevin <b>TRAPP</b>
                                </div>
                                <div class="col-sm-6 col-md-4 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/stegen.jpg"/>
                                        <div class="player-number">22</div>
                                    </div>
                                    Marc André <b>TER STEGEN</b>
                                </div>
                            </div>

                            <h3 class="text-center" style="margin-top: 120px">ABWEHR</h3>
                            <div class="row">
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/plattenhardt.jpg"/>
                                        <div class="player-number">2</div>
                                    </div>
                                    Marvin <b>PLATTENHARDT</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/hector.jpg"/>
                                        <div class="player-number">3</div>
                                    </div>
                                    Jonas <b>HECTOR</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/ginter.jpg"/>
                                        <div class="player-number">4</div>
                                    </div>
                                    Matthias <b>GINTER</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/hummels.jpg"/>
                                        <div class="player-number">5</div>
                                    </div>
                                    Mats <b>HUMMELS</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/suele.jpg"/>
                                        <div class="player-number">15</div>
                                    </div>
                                    Niklas <b>SÜLE</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/ruediger.jpg"/>
                                        <div class="player-number">16</div>
                                    </div>
                                    Antonio <b>RÜDIGER</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/boateng.jpg"/>
                                        <div class="player-number">17</div>
                                    </div>
                                    Jérôme <b>BOATENG</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/kimmich.jpg"/>
                                        <div class="player-number">18</div>
                                    </div>
                                    Joshua <b>KIMMICH</b>
                                </div>
                            </div>

                            <h3 class="text-center" style="margin-top: 120px">MITTELFELD/ANGRIFF</h3>
                            <div class="row">
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/khedira.jpg"/>
                                        <div class="player-number">6</div>
                                    </div>
                                    Sami <b>KHEDIRA</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/draxler.jpg"/>
                                        <div class="player-number">7</div>
                                    </div>
                                    Julian <b>DRAXLER</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/kroos.jpg"/>
                                        <div class="player-number">8</div>
                                    </div>
                                    Toni <b>KROOS</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/werner.jpg"/>
                                        <div class="player-number">9</div>
                                    </div>
                                    Timo <b>WERNER</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/oezil.jpg"/>
                                        <div class="player-number">10</div>
                                    </div>
                                    Mesut <b>ÖZIL</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/reus.jpg"/>
                                        <div class="player-number">11</div>
                                    </div>
                                    Marco <b>REUS</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/mueller.jpg"/>
                                        <div class="player-number">13</div>
                                    </div>
                                    Thomas <b>MÜLLER</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/goretzka.jpg"/>
                                        <div class="player-number">14</div>
                                    </div>
                                    Leon <b>GORETZKA</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/rudy.jpg"/>
                                        <div class="player-number">19</div>
                                    </div>
                                    Sebastian <b>RUDY</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/brandt.jpg"/>
                                        <div class="player-number">20</div>
                                    </div>
                                    Julian <b>BRANDT</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/guendogan.jpg"/>
                                        <div class="player-number">21</div>
                                    </div>
                                    Ilkay <b>GÜNDOGAN</b>
                                </div>
                                <div class="col-sm-6 col-md-3 text-center player-item">
                                    <div class="player-image">
                                        <img src="img/players/gomez.jpg"/>
                                        <div class="player-number">23</div>
                                    </div>
                                    Mario <b>GOMEZ</b>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-12">
                    <div class="info">
                        <div class="page-header">
                            <h2><i class="glyphicon glyphicon-flash"></i> Datenschutz & Impressum</h2>
                        </div>
                        <div class="info-text">
                            <p>Hier gibt es weitere Informationen zum Datenschutz und Disclaimer.</p>
                            <button class="btn btn-default btn-more-infos">Mehr Informationen</button>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <script>
            $('.btn-more-infos').on('click', function (event) {
                event.preventDefault();
                $('#moreInfosModal').modal('show');
            });

            var form = null;
            $('#btn-login').on('click', function (event) {
                event.preventDefault();
                form = 'login';
                formhash($('#login-form'));
            });

            $('#login-form #password, #login-form #email').keypress(function (event) {
                if (event.keyCode === 13) {
                    $('#login-form #btn-login').click();
                }
            });

            $('#login-form').on('submit', function (event) {
                event.preventDefault();
                var formElement = $(this);
                clearAlerts(formElement);

                if (form === 'login') {
                    var data = {email: $('#login-form #email').val().trim(), p: $('#login-form #p').val()};
                    lockButton($(formElement).find('#btn-login'), true, 'fa-unlock-alt');
                    login(data, function (result) {
                        unlockButton($(formElement).find('#btn-login'), true, 'fa-unlock-alt');
                        console.log('login status: ', result, formElement);

                        if (result.status === 'accountLogged') {
                            appendAlert(formElement, ALERT_ACCOUNT_LOGGED);
                        } else if (result.status === 'passwordNotCorrect') {
                            appendAlert(formElement, ALERT_WRONG_PASSWORD);
                        } else if (result.status === 'loginFailed') {
                            appendAlert(formElement, ALERT_LOGIN_FAILED);
                        } else if (result.status === 'noUserExists') {
                            appendAlert(formElement, ALERT_NO_USER_EXISTS);
                        } else if (result.status === 'success') {
                            goto('dashboard.php');
//                            window.location.replace('...');
//                            formElement.trigger('loginSuccess', [result]);
                        } else if (data.status === 'databaseError') {
                            appendAlert(formElement, ALERT_GENERAL_ERROR);
                        }
                    });
                } else if (form === 'forgot') {
                    lockButton($(formElement).find('#btn-forgot-password'), true, 'fa-unlock-alt');
                    requestPasswordReset({email: $('#login-form #email').val().trim()}, function (result) {
                        unlockButton($(formElement).find('#btn-forgot-password'), true, 'fa-unlock-alt');

                        if (result.status === RESULT_SUCCESS) {
                            $('#login-form #email').val('');
                            appendAlert(formElement, ALERT_PASSWORD_RESET_SEND);
                        } else if (result.status === 'emailDoesntExist') {
                            appendAlert(formElement, ALERT_CHECK_EMAIL);
                        } else {
                            appendAlert(formElement, ALERT_GENERAL_ERROR);
                        }
                    });
                }
            });


            // forgot password function
            $(document).on('click', '#btn-forgot-password', function (event) {
                event.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    form = 'forgot';
                    clearAlerts($('#login-form'));
                    forgotFormhash($('#login-form'));
                }
            });


            // register form functions
            $('#btn-open-register').on('click', function (event) {
                event.preventDefault();
                $('#registerModal').modal('show');
            });

            $('#btn-register').on('click', function (event) {
                event.preventDefault();
                if (!$(this).hasClass('disabled')) {
                    clearAlerts($('#register-form'));
                    registerFormhash($('#register-form'));
                }
            });

            $('#register-form').on('submit', function (event) {
                event.preventDefault();
                var button = $('#registerModal').find('#btn-register');

                lockButton(button, true, 'fa-user-plus');
                var formElement = $(this);
                clearAlerts(formElement);

                var email = $('#register-form #email').val().trim();
                var username = $('#register-form #username').val().trim();
                var p = $('#register-form #p').val().trim();

                register({username: username, email: email, p: p}, function (result) {
                    clearAlerts(formElement);
                    unlockButton(button, true, 'fa-user-plus');

                    if (result.status === 'userExists') {
                        appendAlert($('#register-form'), ALERT_USER_EXISTS);
                    } else if (result.status === 'success') {
                        appendAlert($('#register-form'), ALERT_REGISTER_SUCCESS);
                        $('#register-form').find('#form-groups').addClass('hidden');
                        $(button).addClass('hidden');
                        $('#btn-close').removeClass('hidden');
                        formElement.trigger('registerSuccess', [result]);
                    } else if (result.status === 'error') {
                        appendAlert($('#register-form'), ALERT_GENERAL_ERROR);
                    }
                });
            });

            function resetAlerts() {
                $('.alert-space').empty();
            }


        </script>

    </body>
</html>