/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var MONTHS = ["Januar", "Februar", "März", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"];
var DAYS = new Array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");

var PANELS = 'panels';
var CORRECT_WINNER_TIP_POINTS = 4;

var FLAG_IMAGE_PATH = 'img/flags/';
var TV_IMAGE_PATH = 'img/';
var COUNTRIES = 'countries';
var WINNER_TIP = 'winnerTip';
var WINNER_TIPS = 'winnerTips';
var USER_TIPS = 'userTips';
var GAMES = 'games';
var TIPS = 'tips';
var USERS = 'users';

// rounds
var PRELIMINARY = 'preliminary';
var LAST_SIXTEEN = 'lastSixteen';
var QUARTER_FINALS = 'quarterfinals';
var SEMI_FINALS = 'semifinals';
var SMALL_FINAL = 'smallfinal';
var FINAL = 'final';

// alerts
var ALERT_GENERAL_ERROR = 'general-error';
var ALERT_MISSING_FIELDS = 'missing-fields';
var ALERT_MISSING_EMAIL = 'missing-email';
var ALERT_LOGIN_FAILED = 'login-failed';
var ALERT_NO_USER_EXISTS = 'no-user-exists';
var ALERT_ACCOUNT_LOGGED = 'account-logged';
var ALERT_INVALID_EMAIL = 'invalid-email';
var ALERT_WRONG_PASSWORD = 'wrong-password';
var ALERT_PASSWORD_SHORT = 'password-short';
var ALERT_CHECK_PASSWORD = 'check-password';
var ALERT_PASSWORD_RESET_SEND = 'password-reset-send';
var ALERT_PASSWORD_INVALID = 'password-invalid';
var ALERT_PASSWORDS_NOT_MATCHING = 'passwords-not-matching';
var ALERT_USER_EXISTS = 'user-exists';
var ALERT_REGISTER_SUCCESS = 'register-success';
var ALERT_TIP_EXISTS = 'tip-exists';
var ALERT_PASSWORD_RESET_SUCCESS = 'password-reset-success';
var ALERT_RESET_PASSWORD_ERROR = 'reset-password-error';
var ALERT_CHECK_EMAIL = 'check-email';

var ALERT_NO_TIPS = 'no-tips';
var ALERT_NO_RESULTS = 'no-results';

var RESULT_SUCCESS = 'success';

//var POSSIBLE_WINNER_TIP_DATE = 1464966991000;
var POSSIBLE_WINNER_TIP_DATE = 1531674000000;//1466798400000;