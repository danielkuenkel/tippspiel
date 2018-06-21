// ajax functions calling php services

function getQueryParams(qs) {
    qs = qs.split('+').join(' ');

    var params = {},
            tokens,
            re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
    }

    return params;
}

function ajaxError(xhr, desc, err) {
    appendAlert($('body'), ALERT_GENERAL_ERROR);
    console.log(xhr);
    console.log("Details: " + desc + "\nError:" + err);
}

function login(data, callback) {
    $.ajax({
        url: 'includes/process_login.php',
        data: data,
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function register(data, callback) {
    $.ajax({
        url: 'includes/register.php',
        data: data,
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function requestPasswordReset(data, callback) {
    $.ajax({
        url: 'includes/request-password-reset.php',
        data: data,
        type: 'post',
        async: true,
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function resetPassword(data, callback) {
    $.ajax({
        url: 'includes/reset-password.php',
        data: data,
        type: 'post',
        async: true,
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function getSchedule() {
    $.ajax({
        url: 'includes/get_schedule.php',
        type: 'post',
        success: function (data) {
            removeAlert($('#main-content'), ALERT_GENERAL_ERROR);
            if (data.status === 'requestError') {
                appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
            } else if (data.games && data.games.length > 0 &&
                    data.countries && data.countries.length > 0) {
                removeLocalItem(USER_TIPS);
                setLocalItem(GAMES, data.games);
                setLocalItem(COUNTRIES, data.countries);
                if (data.tips) {
                    setLocalItem(USER_TIPS, data.tips);
                }
                if (data.winnerTip) {
                    setLocalItem(WINNER_TIP, data.winnerTip);
                }
                if (data.users) {
                    setLocalItem(USERS, data.users);
                }
                renderDashboard(true);
                renderCountryDropdown();
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function getChartData(callback) {
    $.ajax({
        url: 'includes/get_chart_data.php',
        type: 'post',
        async: true,
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function getGames(callback) {
    $.ajax({
        url: 'includes/get_games.php',
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function updateGame(data, callback) {
    $.ajax({
        url: 'includes/update_game.php',
        data: data,
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function createGame(data, callback) {
    $.ajax({
        url: 'includes/create_game.php',
        data: data,
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function deleteGame(data, callback) {
    $.ajax({
        url: 'includes/delete_game.php',
        data: data,
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function submitTip(sumbitData, callback) {
    $.ajax({
        url: 'includes/submit_tip.php',
        data: sumbitData,
        type: 'post',
        success: function (result) {
            if (callback) {
                callback(result);
            }
        },
        error: function (xhr, desc, err) {
            ajaxError(xhr, desc, err);
        }
    });
}

function submitWinnerTip(sumbitData) {
    $.ajax({
        url: 'includes/submit_winner_tip.php',
        data: sumbitData,
        type: 'post',
        success: function (data, status) {
            removeAlert($('#main-content'), ALERT_GENERAL_ERROR);
            if (data.status === 'success') {
                getWinnerTips();
            } else {
                appendAlert($('#main-content'), ALERT_GENERAL_ERROR)
            }
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
            appendAlert($('#main-content'), ALERT_GENERAL_ERROR)
        }
    });
}

function getGrouplist() {
    $.ajax({
        url: 'includes/grouplist.php',
        type: 'post',
        success: function (data, status) {
            removeAlert($('#main-content'), ALERT_GENERAL_ERROR);
            if (data.status === 'requestError') {
                appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
            } else if (data.countries && data.countries.length > 0) {
                setLocalItem(COUNTRIES, data.countries);
                renderGrouplist();
            }
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
            appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
        }
    });
}

function getWinnerTips() {
    $.ajax({
        url: 'includes/get_winner_tip.php',
        type: 'post',
        success: function (data, status) {
            removeAlert($('#main-content'), ALERT_GENERAL_ERROR);
            if (data.status === 'requestError') {
                appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
            } else if (data.countries && data.countries.length > 0 && data.winnerTip) {
                setLocalItem(COUNTRIES, data.countries);
                setLocalItem(WINNER_TIP, data.winnerTip);
                renderWinnerTips();
            }
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
            appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
        }
    });
}

function getToplist() {
    $.ajax({
        url: 'includes/toplist.php',
        type: 'post',
        success: function (data, status) {
            removeAlert($('main-content'), ALERT_GENERAL_ERROR);
            if (data.status === 'noResults') {
                appendAlert($('#main-content'), ALERT_NO_RESULTS);
            } else if (data.status === 'noTips') {
                appendAlert($('#main-content'), ALERT_NO_TIPS);
            } else if (data.status === 'requestError') {
                appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
            } else if (data.tips && data.tips.length > 0 &&
                    data.games && data.games.length > 0 &&
                    data.users && data.users.length > 0) {
                removeLocalItem(WINNER_TIPS);
                setLocalItem(GAMES, data.games);
                setLocalItem(TIPS, data.tips);
                setLocalItem(USERS, data.users);
                setLocalItem(WINNER_TIPS, data.winnerTips);
                setLocalItem(COUNTRIES, data.countries);
                setLocalItem('userId', data.userId);
                renderToplist();
            }
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
            appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
        }
    });
}

function getUsers(callback) {
    $.ajax({
        url: 'includes/get_users.php',
        type: 'post',
        success: function (data) {
            if (data.status === 'success') {
                if (callback) {
                    callback(data.users);
                }
            } else {
            }
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
            appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
        }
    });
}

function updateUser(data, callback) {
    $.ajax({
        url: 'includes/update_user.php',
        data: data,
        type: 'post',
        success: function (data) {
            if (callback) {
                callback(data);
            }
        },
        error: function (xhr, desc, err) {
            console.log(xhr);
            console.log("Details: " + desc + "\nError:" + err);
            appendAlert($('#main-content'), ALERT_GENERAL_ERROR);
        }
    });
}




// general functions for every page

function calculatePoints(userTip) {
    var game = getGameById(userTip.gameId);

    if (game !== null && game.goalsParticipatorA !== null && game.goalsParticipatorB !== null) {

        if ((parseInt(game.goalsParticipatorA) - (userTip.goalsParticipatorA) < 0 && game.goalsParticipatorB - (userTip.goalsParticipatorB) > 0) ||
                (parseInt(game.goalsParticipatorB) - (userTip.goalsParticipatorB) < 0 && game.goalsParticipatorA - (userTip.goalsParticipatorA) > 0)) {
            // 0 Punkte
            return 0;
        }

        var differenceA = Math.abs(parseInt(game.goalsParticipatorA) - (userTip.goalsParticipatorA));
        var differenceB = Math.abs(parseInt(game.goalsParticipatorB) - (userTip.goalsParticipatorB));
        var totalDifference = Math.abs(differenceA - differenceB);

        if (differenceA === 0 && differenceB === 0) {
            // überrangend getippt 5 Punkte
            return 5;
        } else if ((differenceA === 1 && differenceB === 0) || (differenceA === 0 && differenceB === 1)) {
            // Tendenz gut - 1 Punkt
            return 1;
        } else if (game.goalsParticipatorA !== game.goalsParticipatorB && totalDifference === 0 && differenceA <= 1 && differenceB <= 1) {
            // gutes Verhältnis getippt 3 Punkte
            return 3;
        } else if (game.goalsParticipatorA === game.goalsParticipatorB && differenceA === 1 && differenceB === 1) {
            // gutes Verhältnis getippt 3 Punkte
            return 3;
        } else if (game.goalsParticipatorA !== game.goalsParticipatorB && totalDifference === 0 && differenceA <= 2 && differenceB <= 2) {
            // weniger gutes Verhältnis getippt 2 Punkte
            return 2;
        } else if (game.goalsParticipatorA === game.goalsParticipatorB && differenceA === 2 && differenceB === 2) {
            // weniger gutes Verhältnis getippt 2 Punkte
            return 2;
        }

        // sonst 0 Punkte
        return 0;
    }
    return 0;
}

function hasTippedWinnerCorrectly(tip) {
//    console.log(getGameWinner(tip.gameId));
    var gameWinner = getGameWinner(tip.gameId);
    var tippedGameWinner = getTipWinnerGame(tip);
    var penaltyWinner = getPenaltyGameWinner(tip.gameId);
//    console.log(gameWinner + ", " + tippedGameWinner);
    if ((tippedGameWinner !== null && gameWinner !== null && tippedGameWinner === gameWinner) || (tippedGameWinner !== null && penaltyWinner !== null && tippedGameWinner === penaltyWinner)) {
//        console.log('correct tip');
        return true;
    } else {
        return false;
    }
}

function getTipWinnerGame(tip) {
    var game = getGameById(tip.gameId);

    if (game !== null) {
        if (tip.goalsParticipatorA > tip.goalsParticipatorB) {
            return game.participatorA;
        } else if (tip.goalsParticipatorA < tip.goalsParticipatorB) {
            return game.participatorB;
        } else {
            return 'draw';
        }

    }
    return null;
}

function getGameWinner(gameId) {
    var game = getGameById(gameId);

    if (game !== null && game.goalsParticipatorA !== null && game.goalsParticipatorB !== null) {
        if (game.goalsParticipatorA > game.goalsParticipatorB) {
            return game.participatorA;
        } else if (game.goalsParticipatorA < game.goalsParticipatorB) {
            return game.participatorB;
        } else {
            return 'draw';
        }
    }
    return null;
}

function getPenaltyGameWinner(gameId) {
    var game = getGameById(gameId);

    if (game !== null && game.goalsPenaltyParticipatorA !== null && game.goalsPenaltyParticipatorB !== null)
    {
        if (game.goalsPenaltyParticipatorA > game.goalsPenaltyParticipatorB) {
            return game.participatorA;
        } else if (game.goalsPenaltyParticipatorA < game.goalsPenaltyParticipatorB) {
            return game.participatorB;
        }
    }

    return null;
}

function calculateFinalPoints(userId) {
    var winnerTips = getLocalItem(WINNER_TIPS);
    if (winnerTips === null) {
        winnerTips = getLocalItem(WINNER_TIP);
    }

    if (winnerTips && winnerTips.length > 0) {
        for (var i = 0; i < winnerTips.length; i++) {

            if (parseInt(winnerTips[i].userId) === parseInt(userId))
            {
                if (winnerTips[i].iso === getFinalGameWinner()) {
                    return 20;
                } else {
                    return 0;
                }
            }
        }
        return 0;
    } else {
        return 0;
    }
}

function getFinalGameWinner() {
    var games = getLocalItem(GAMES);
    for (var i = 0; i < games.length; i++) {
        if (games[i].round === 'final' && games[i].goalsParticipatorA !== null && games[i].goalsParticipatorB !== null) {
            return getGameWinner(games[i].id);
        }
    }
    return 'xx';
}

function getGameById(gameId) {
    var games = getLocalItem(GAMES);
    for (var i = 0; i < games.length; i++) {
        if (parseInt(gameId) === parseInt(games[i].id)) {
            return games[i];
        }
    }
    return null;
}

function getTippedGames() {
    var games = getLocalItem(GAMES);
    var finishedGames = new Array();

    for (var i = 0; i < games.length; i++) {
        if (!isTimeLeft(games[i].timestamp * 1000)) {
            finishedGames.push(games[i]);
        }
    }

    return finishedGames;
}

function getCountryByIso(iso) {
    var countries = getLocalItem(COUNTRIES);
    if (countries) {
        for (var i = 0; i < countries.length; i++) {
            if (countries[i].iso === iso) {
                return countries[i];
            }
        }
    }
    return null;
}

function sortByKey(array, key, reverse) {
    return array.sort(function (a, b) {
        var x = a[key];
        var y = b[key];
        if (reverse) {
            x = b[key];
            y = a[key];
        }

        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

function isTimeLeft(timestamp) {
    if (Date.now() >= timestamp) {
//        console.log('times up');
        return false;
    } else {
//        console.log("there is time");
        return true;
    }
}

function getTimeLeftForTimestamp(timestamp) {
    var dateNow = new Date();
    var seconds = Math.floor((timestamp - (dateNow)) / 1000);
    var minutes = Math.floor(seconds / 60);
    var hours = Math.floor(minutes / 60);
    var days = Math.floor(hours / 24);
    hours = hours - (days * 24);
    minutes = minutes - (days * 24 * 60) - (hours * 60);
    seconds = seconds - (days * 24 * 60 * 60) - (hours * 60 * 60) - (minutes * 60);
//    return days + ' Tage, ' + hours + ' Stunden, ' + minutes + ' Minunten und ' + seconds + ' Sekunden';
    return {days: days, hours: hours, minutes: minutes, seconds: seconds};
}

function getDate(timestamp) {
    var date = new Date(timestamp * 1000);
    var month = date.getMonth() < 10 ? "0" + (date.getMonth() + 1) : (date.getMonth + 1);
    var day = date.getDate() < 10 ? ("0" + date.getDate()) : date.getDate();
    return DAYS[date.getDay()] + ", " + day + "." + month + "." + date.getFullYear();
}

function getTime(timestamp) {
    var date = new Date(timestamp * 1000);
    var minutes = date.getMinutes() < 10 ? '0' + date.getMinutes() : date.getMinutes();
    return date.getHours() + ":" + minutes + " Uhr";
}


/*
 * (un)lock buttons, if waiting for system functionalitity (e.g. database update)
 */

function lockButton(button, showLoadingIndicator, originalIcon) {
    $(button).addClass('disabled');
    if (showLoadingIndicator && showLoadingIndicator === true && originalIcon) {
        $(button).find('.fa').removeClass(originalIcon).addClass('fa-spin fa-circle-o-notch');
    } else if (showLoadingIndicator && showLoadingIndicator) {
        $(button).find('.fa').addClass('fa-spin fa-circle-o-notch');
    }
}

function unlockButton(button, hideLoadingIndicator, originalIcon) {
    $(button).removeClass('disabled');
    if (hideLoadingIndicator && hideLoadingIndicator === true && originalIcon) {
        $(button).find('.fa').removeClass('fa-spin fa-circle-o-notch').addClass(originalIcon);
    } else if (hideLoadingIndicator && hideLoadingIndicator === true) {
        $(button).find('.fa').removeClass('fa-spin fa-circle-o-notch');
    }
}

function goto(url) {
    window.location.href = url;
}



$(document).on('click', '.btn-checkbox, .btn-radio', function (event) {
    event.preventDefault();
    if (event.handled !== true && !$(this).hasClass('disabled'))
    {
        event.handled = true;
        if ($(this).hasClass('btn-checkbox') && $(this).hasClass('btn-option-checked')) {
            uncheckOption($(this));
        } else {
            checkOption($(this));
        }

        if ($(this).hasClass('saveGeneralData')) {
            saveGeneralData();
        }
    }
});

function uncheckOption(optionItem) {
    $(optionItem).removeClass('btn-option-checked');
    $(optionItem).find('#normal').removeClass('hidden');
    $(optionItem).find('#checked').addClass('hidden');
    $(optionItem).trigger('change');
}

function checkOption(optionItem) {
    if ($(optionItem).hasClass('btn-radio')) {
        var children = $(optionItem).closest('.root').find('.btn-radio');
        $(children).removeClass('btn-option-checked');
        $(children).find('#normal').removeClass('hidden');
        $(children).find('#checked').addClass('hidden');
    }

    $(optionItem).addClass('btn-option-checked');
    $(optionItem).find('#over, #normal').addClass('hidden');
    $(optionItem).find('#checked').removeClass('hidden');
    $(optionItem).trigger('change');
}

$(document).on('focus focusin select', '.optionalInput', function (event) {
    if (event.handled !== true)
    {
        event.handled = true;
        if (($(this).val().trim() === '' && !$(this).parent().find('.btn-radio, .btn-checkbox').hasClass('btn-option-checked')) ||
                ($(this).val().trim() !== '' && !$(this).parent().find('.btn-radio, .btn-checkbox').hasClass('btn-option-checked'))) {
            $(this).parent().find('.btn-radio, .btn-checkbox').click();
        }
    }
});

$(document).on('focusout', '.optionalInput', function (event) {
    if (event.handled !== true)
    {
        event.handled = true;
        var btnChecked = $(this).parent().find('.btn-option-checked');
        if ($(this).val().trim() === '') {
            btnChecked.click();
        }
    }
});

$(document).on('mouseover', '.btn-checkbox, .btn-radio', function () {
    if (!$(this).hasClass('btn-option-checked')) {
        $(this).find('#normal, #checked').addClass('hidden');
        $(this).find('#over').removeClass('hidden');
    }

});

$(document).on('mouseleave', '.btn-checkbox, .btn-radio', function () {
    if (!$(this).hasClass('btn-option-checked')) {
        $(this).find('#normal').removeClass('hidden');
        $(this).find('#over, #checked').addClass('hidden');
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