/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var timer = null;
var totalPossibleTips = 0;
var tipsSubmitted = 0;
var currentScore = 0;
var gamesEnabled = 0;
var gamesDone = 0;
var savePanelStatus = true;

function renderDashboard(isFirstRender) {
    var games = getLocalItem(GAMES);
//    var panels = getLocalItem(PANELS);
//    console.log("render games: " + games);

    totalPossibleTips = 0;
    tipsSubmitted = 0;
    currentScore = 0;
    gamesDone = 0;
    savePanelStatus = false;
    var currentPanelId, visiblePanel = null;
    var tweenCount = 0;
    CSSPlugin.useSVGTransformAttr = true;

    for (var i = 0; i < games.length; i++) {
        
        

        var panel = $('#panel-' + games[i].round);
        var item = panel.find('#game-' + games[i].id);
        item.addClass('tableRow');
        
//        console.log(item)

        if (item.length !== 1) {
            item = $('#dashboard-schedule-item').clone().removeAttr('id').removeClass('hidden');
            item.attr('id', 'game-' + games[i].id);
            item.attr('data-time', games[i].timestamp);
            panel.append(item);
//            console.log(games[i], panel, item);
            appendEvents(item);
        }

        if (currentPanelId !== games[i].round) {
            currentPanelId = games[i].round;
//            console.log(panels[currentPanelId]);

            if (shouldPanelBeVisible(currentPanelId)) {
                visiblePanel = currentPanelId;
//                console.log(panel);
                $(panel).closest('.tip-content').find('.btn-show-panel-body').click();
            }

//            if (panels) {
//                if (panels[currentPanelId] && panels[currentPanelId] === true) {
//                    panel.closest('.panel').find('.btn-hide-panel-body').click();
//                } else {
//                    panel.closest('.panel').find('.btn-show-panel-body').click();
//                }
//            }
        }

        if (isFirstRender && visiblePanel === currentPanelId) {
//            var startX = tweenCount % 2 === 0 ? -20 : 20;
//            TweenMax.from(item, .4, {x: -40, opacity: 0, ease: Elastic.easeOut, delay: (tweenCount * .05)});
//            TweenMax.from(item, .3, {x: startX, opacity: 0, delay: (tweenCount * 0.1)});
            tweenCount++;
        }

        if (games[i].timestamp !== "") {
            item.find('.date').text(getDate(games[i].timestamp));
            item.find('.time').text(getTime(games[i].timestamp));

            item.find('.time-left').css('color', 'black');

            if (games[i].timestamp * 1000 > new Date()) {
                var timeLeft = getTimeLeftForTimestamp(games[i].timestamp * 1000);
                item.find('.time-left').removeClass('hidden');
                item.find('.time-left').text('in ' + (timeLeft.days === 1 ? timeLeft.days + ' Tag ' : timeLeft.days + " Tagen ") + timeLeft.hours + " Std. " + timeLeft.minutes + ' Min.');
//                console.log(timeLeft);
//                var earliestVisibleDate = (timeBegin.getTime()) + -1 * 24 * 3600 * 1000; // date 1 day befor the earliestDate in milliseconds 
                if (timeLeft.days === 0) {
                    item.find('.progress').removeClass('hidden');
                    item.find('.progress-bar').removeClass('progress-bar-info');
                    item.find('.progress-bar').removeClass('progress-bar-default');
                    item.find('.progress-bar').removeClass('progress-bar-danger');
                    if (timeLeft.hours >= 12) {
                        item.find('.progress-bar').addClass('progress-bar-info');
                    } else if (timeLeft.hours >= 1) {
                        item.find('.progress-bar').addClass('progress-bar-default');
                    } else if (timeLeft.hours >= 0) {
                        item.find('.time-left').css('color', 'brown');
                        item.find('.progress-bar').addClass('progress-bar-danger');
                    }
                }
            } else if (games[i].goalsParticipatorA === null && games[i].goalsParticipatorB === null) {
                item.find('.progress').removeClass('hidden');
                item.find('.progress-bar').removeClass('progress-bar-info');
                item.find('.progress-bar').addClass('progress-bar-warning');
            }

            checkUserTips(item, games[i]);
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

            item.find('.tv').addClass('hidden');
            item.find('.date').addClass('hidden');
            item.find('.time').addClass('hidden');
            item.find('.result-header').removeClass('hidden');
            item.find('.result').removeClass('hidden');
            item.find('.location').addClass('hidden');
            gamesDone++;

            if (games[i].round === 'final' && games[i].participatorA !== null && games[i].participatorB != null) {
                currentScore += calculateFinalPoints(getLocalItem(WINNER_TIP)[0].userId);
            }
        } else {
            if (games[i].tv) {
                item.find('.tv').attr('src', TV_IMAGE_PATH + games[i].tv + '.png');
            } else {
                item.find('.tv').addClass('hidden');
            }
        }


        if (games[i].participatorA !== null && games[i].participatorB !== null) {
            totalPossibleTips++;
            gamesEnabled++;
            item.find('.user-tip').removeClass('hidden');
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
                    $(item).find('.done').removeClass('hidden');
                    $(item).find('.actual').addClass('hidden');
                    $(item).find('.progress').addClass('hidden');
                    $(item).find('.user-tip').addClass('hidden');
                }
            }
        } else {
            tipSubmitImpossible(item);
            item.find('.participator-a .flag').attr('src', FLAG_IMAGE_PATH + 'xx.png');
            item.find('.participator-b .flag').attr('src', FLAG_IMAGE_PATH + 'xx.png');
        }
    }

    $('#abstract #submitted-tips').text(tipsSubmitted);
    $('#abstract #submitted-tips-of').text(tipsSubmitted === 1 ? 'Tipp von' : 'Tipps von');
    $('#abstract #tips-total').text(totalPossibleTips);
    $('#abstract #tips-total').text(totalPossibleTips);
    $('#abstract #current-score').text(currentScore);
    $('#abstract #total-score').text((games.length * 9) + 20);
    $('#abstract #games-total').text(games.length);
    $('#abstract #games-left').text(games.length - gamesDone);
    $('#abstract #total-jackpot').text(getLocalItem(USERS).length * 2);


    renderOverview();
    renderUsers();
    savePanelStatus = true;

    clearTimeout(timer);
    timer = setTimeout(renderDashboard, 15000);
}

// if the timer runs up for a game
function checkUserTips(panel, game) {

    var userTips = getLocalItem(USER_TIPS);
    var panelGameId = $(panel).attr('id').split('-')[1];
    var hasTip = false;

    if (userTips && userTips.length > 0) {
        for (var i = 0; i < userTips.length; i++) {
            if (parseInt(panelGameId) === parseInt(userTips[i].gameId)) {
                // tip for this game and user exist in DB
                tipSubmitted(panel, game, userTips[i]);
                hasTip = true;
                tipsSubmitted++;
                break;
            }
        }
    }

    if (!hasTip) {
        if (!isTimeLeft(game.timestamp * 1000)) {
            // time's running up
            noTipSubmitted(panel);
        } else {
            tipSubmitPossible(panel);
        }
    }
}

function tipSubmitted(panel, game, tip) {
    $(panel).find('.btn-add-tip').addClass('hidden');
    $(panel).find('.user-tip-container').removeClass('hidden');
    $(panel).find('.tip-overview').removeClass('hidden');
    $(panel).find('.user-tip-text .goals-a').text(tip.goalsParticipatorA);
    $(panel).find('.user-tip-text .goals-b').text(tip.goalsParticipatorB);
    $(panel).find('.no-tip-submitted').addClass('hidden');
    $(panel).find('.submit-tip-form').addClass('hidden');


    if (game.goalsParticipatorA !== null && game.goalsParticipatorB !== null) {
        $(panel).find('.valuation').removeClass('hidden');
        var earnedPoints = calculatePoints(tip);

        var winnerTippedCorrect = hasTippedWinnerCorrectly(tip);
        if (winnerTippedCorrect) {
            $(panel).find('.winner-tip-correct').removeClass('hidden');
            currentScore += CORRECT_WINNER_TIP_POINTS;
        }

        var points = earnedPoints + (winnerTippedCorrect ? CORRECT_WINNER_TIP_POINTS : 0);
        $(panel).find('.pointsDone').text(points === 1 ? points + ' Punkt' : points + ' Punkte');

        var pointsText = "0 Punkte";
        currentScore += earnedPoints;

        switch (earnedPoints) {
            case 5: // überrangend getippt 5 Punkte
                $(panel).find('.tip-was').text("überragend");
                $(panel).find('.valuation-icon').addClass('glyphicon glyphicon-thumbs-up');
                pointsText = "5 Punkte";
                break;
            case 3: // Differenz sehr gut
                $(panel).find('.tip-was').text("verhältnismäßig gut");
                pointsText = "3 Punkte";
                break;
            case 2: // Differenz gut
                $(panel).find('.tip-was').text("verhältnismäßig weniger gut");
                pointsText = "2 Punkte";
                break;
            case 1: // Tendenz gut
                $(panel).find('.tip-was').text("tendenziell gut");
                $(panel).find('.valuation-icon').addClass('glyphicon glyphicon-thumbs-up');
                pointsText = "1 Punkt";
                break;
            case 0: // das war wohl nichts 0 Punkte
                $(panel).find('.tip-was').text("... reden wir nicht weiter drüber!");
                $(panel).find('.valuation-icon').addClass('glyphicon glyphicon-thumbs-down');
                break;
        }

        if (!isTimeLeft(game.timestamp * 1000)) {
            $(panel).find('.progress-bar').removeClass('progress-bar-info');
            $(panel).find('.progress-bar').removeClass('progress-bar-default');
            $(panel).find('.progress-bar').removeClass('progress-bar-danger');
            $(panel).find('.progress-bar').removeClass('progress-bar-warning');
            $(panel).find('.progress').removeClass('hidden');

            if (winnerTippedCorrect || earnedPoints > 0) {
                $(panel).addClass('panel-success');
                $(panel).find('.progress-bar').removeClass('progress-bar-info active progress-bar-striped');
                $(panel).find('.progress-bar').addClass('progress-bar-success');
                $(panel).find('.panel-heading').css({borderBottomRightRadius: '10px', borderBottomLeftRadius: '10px'});
            } else {
                $(panel).find('.progress-bar').removeClass('progress-bar-info active progress-bar-striped');
                $(panel).find('.progress-bar').addClass('progress-bar-danger');
                $(panel).find('.panel-heading').css({borderBottomRightRadius: '10px', borderBottomLeftRadius: '10px'});
                $(panel).addClass('panel-danger');
            }
        }

        $(panel).find('.points').text(pointsText);

    } else {
        $(panel).find('.waitingForResult').removeClass('hidden');
        if (!isTimeLeft(game.timestamp * 1000)) {
            $(panel).find('.progress-bar').removeClass('progress-bar-info');
            $(panel).find('.progress-bar').removeClass('progress-bar-default');
            $(panel).find('.progress-bar').removeClass('progress-bar-danger');
            $(panel).find('.progress-bar').removeClass('progress-bar-warning');
            $(panel).addClass('panel-warning');
            $(panel).find('.progress-bar').addClass('progress-bar-warning');
        } else {
            $(panel).addClass('panel-info');
        }
    }
}

function noTipSubmitted(panel) {
    $(panel).addClass('panel-danger');
    $(panel).find('.btn-add-tip').addClass('hidden');
    $(panel).find('.submit-tip-form').addClass('hidden');
    $(panel).find('.tip-overview').removeClass('hidden');
    $(panel).find('.no-tip-submitted').removeClass('hidden');
    $(panel).find('.user-tip-container').addClass('hidden');
    $(panel).find('.progress').removeClass('hidden');
    $(panel).find('.progress-bar').removeClass('progress-bar-info active progress-bar-striped');
    $(panel).find('.progress-bar').addClass('progress-bar-danger');
}

function tipSubmitPossible(panel) {
    $(panel).find('.btn-add-tip').removeClass('hidden');
    $(panel).find('.user-tip-container').addClass('hidden');
    $(panel).find('.no-tip-submitted').addClass('hidden');
}

function tipSubmitImpossible(panel) {
    $(panel).find('.user-tip').addClass('hidden');
    $(panel).find('.panel-heading').css({borderBottomRightRadius: '10px', borderBottomLeftRadius: '10px'});
}

function tipExistsAfterSubmit(panel) {
    $(panel).find('.submit-tip-form').addClass('hidden');
    $(panel).find('.tip-overview').addClass('hidden');
}

function appendEvents(item) {
    item.find('.btn-add-tip').on('click', function (event) {
        event.preventDefault();
        var panel = $(this).closest('.root');
        $(panel).find('.submit-tip-form').removeClass('hidden');
        $(panel).find('.tip-overview').addClass('hidden');
    });

    item.find('.btn-cancel-submit-tip').on('click', function (event) {
        event.preventDefault();
        var panel = $(this).closest('.root');
        $(panel).find('.submit-tip-form').addClass('hidden');
        $(panel).find('.tip-overview').removeClass('hidden');
    });

    item.find('.btn-submit-tip').on('click', function (event) {
        event.preventDefault();
        if (!$(this).hasClass('disabled')) {
            var button = $(this);
            lockButton(button, true, 'fa-check');
            var panel = $(button).closest('.root');
            $(this).addClass('disabled');
            var tipTime = $(button).closest('.root').attr('data-time');
            var gameId = $(button).closest('.root').attr('id').split('-')[1];
            var goalA = panel.find('.goal-a').val();
            var goalB = panel.find('.goal-b').val();
            submitTip({gameId: gameId, goalsA: goalA, goalsB: goalB, tipTime: tipTime}, function (result) {
                unlockButton($(button), true, 'fa-check');
                removeAlert($('#main-content'), ALERT_GENERAL_ERROR);
                if (result.status === 'exists') {
                    tipExists(gameId);
                } else if (result.status === RESULT_SUCCESS) {
                    getSchedule();
                } else if (result.status === 'tooLate') {
                    $(panel).find('.btn-cancel-submit-tip').click();
                }
            });
        }
    });


    $(item).find('.simple-stepper .btn-stepper-decrease').on('click', function (event) {
        if (event.handled !== true)
        {
            event.handled = true;
            event.preventDefault();
            var min = parseInt($(this).val());
            var currentValue = parseInt($(this).closest('.simple-stepper').find('.stepper-text').val());
            if (currentValue > min) {
                currentValue--;
            } else {
                currentValue = min;
            }
            $(this).closest('.simple-stepper').find('.stepper-text').val(currentValue);
        }
    });

    $(item).find('.simple-stepper .btn-stepper-increase').on('click', function (event) {
        if (event.handled !== true)
        {
            event.handled = true;
            event.preventDefault();
            var max = parseInt($(this).val());
            var currentValue = parseInt($(this).closest('.simple-stepper').find('.stepper-text').val());
            if (currentValue < max) {
                currentValue++;
            } else {
                currentValue = max;
            }
            $(this).closest('.simple-stepper').find('.stepper-text').val(currentValue);
        }
    });
}

function tipExists(gameId) {
    var gamePanel = $('#main-content').find('#game-' + gameId);
    appendAlert(gamePanel, ALERT_TIP_EXISTS);
    tipExistsAfterSubmit(gamePanel);

    $(gamePanel).find('.btn-reload-dashboard').on('click', function (event) {
        event.preventDefault();
        removeAlert(gamePanel, ALERT_TIP_EXISTS);
        clearTimeout(timer);
        getSchedule();
    });
}


function resetContent() {
    $('#panel-preliminary').empty();
    $('#panel-lastSixteen').empty();
    $('#panel-quarterfinals').empty();
    $('#panel-semifinals').empty();
    $('#panel-final').empty();
}



function renderCountryDropdown() {
    var countries = getLocalItem(COUNTRIES);
    var dropdown = $('.countrySelect');

    if (countries && countries.length > 0)
    {
        countries = sortByKey(countries, 'name');
        $(dropdown).find('.option').empty();
        $(dropdown).find('.dropdown-toggle').removeClass('disabled');

        $(dropdown).parent().find('.show-dropdown').unbind('click').bind('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            onShowDropdownClicked($(this));
        });

        $(dropdown).parent().find('.show-dropdown').unbind('change').bind('change', function (event) {
            event.preventDefault();
            renderPlaySessions($(this).parent().find('.chosen').attr('id'));
        });

        var listItem;
        for (var i = 0; i < countries.length; i++) {
            listItem = document.createElement('li');
            listItem.setAttribute('id', countries[i].iso);

            $(listItem).unbind('click').bind('click', function (event) {
                event.preventDefault();
                onListItemClicked($(this));
                $(this).closest('.input-group').find('.item-input-text').change();
            });

            var link = document.createElement('a');
            link.setAttribute('href', '#');
            link.appendChild(document.createTextNode(countries[i].name));
            listItem.appendChild(link);
            $(dropdown).find('.option').append(listItem);
        }
        $('body').find('.option-trigger').attr('placeholder', 'Bitte wählen');

        if (getLocalItem('currentSelectedCountry') && getLocalItem('currentSelectedCountry').iso) {
            var countryIso = getLocalItem('currentSelectedCountry').iso;
            dropdown.find('#' + countryIso).addClass('selected');
            dropdown.find('.chosen').attr('id', countryIso);
            dropdown.prev().val(getCountryByIso(countryIso).name);
            renderPlaySessions(countryIso);
        } else {
            var countryIso = 'de';
            dropdown.find('#' + countryIso).addClass('selected');
            dropdown.find('.chosen').attr('id', countryIso);
            dropdown.prev().val(getCountryByIso(countryIso).name);
            renderPlaySessions(countryIso);
        }
    }
}

function onListItemClicked(source) {

    if ($(source).hasClass('dropdown-header') || $(source).hasClass('divider')) {
        return false;
    }

    if (!$(source).hasClass('selected')) {
        var parent = $(source).closest('.select');
        var itemText = $(source).children().text();
        var listItemId = $(source).attr('id');
        $(parent).find('.chosen').attr('id', listItemId);
        $(parent).prev().val(itemText);
        $(source).parent().children('li').removeClass('selected');
        $(source).addClass('selected');
        setLocalItem("currentSelectedCountry", {iso: $(source).attr('id')});
    }

    if ($(parent).prev().is('input')) {
        // is input
        $(parent).prev().val(itemText);
    } else {
        // has no input nearby
        $(parent).find('.chosen').text(itemText);
    }

    var disabledElements = $(parent).children('.dropdown-disabled');
    if (disabledElements.length > 0) {
        for (var i = 0; i < disabledElements.length; i++) {
            $(disabledElements[i]).removeClass('disabled');
        }
    }
}

function onShowDropdownClicked(source) {
    $(source).parent().find('[data-toggle=dropdown]').dropdown('toggle');
}

function renderPlaySessions(countryIso) {
    $('#country-games').find('.country-playing-container').empty();
    var games = getLocalItem(GAMES);
    if (games && games.length > 0) {
        for (var i = 0; i < games.length; i++) {
            if (games[i].participatorA === countryIso || games[i].participatorB === countryIso) {
                var countyPlaying = document.createElement('div');
                $(countyPlaying).text(getDate(games[i].timestamp) + ', ' + getTime(games[i].timestamp));
                $('#country-games').find('.country-playing-container').append(countyPlaying);
            }
        }
    }
}


function saveOverview() {
    if (savePanelStatus) {
        var overviewHidden = $('#general-overview').find('.btn-hide-panel-body').hasClass('hidden');
        var rulesHidden = $('#general-rules').find('.btn-hide-panel-body').hasClass('hidden');
        var participantsHidden = $('#general-participants').find('.btn-hide-panel-body').hasClass('hidden');
//        var preliminary = $('#preliminary').find('.btn-hide-panel-body').hasClass('hidden');
//        var lastSixteen = $('#lastSixteen').find('.btn-hide-panel-body').hasClass('hidden');
//        var quarterfinals = $('#quarterfinals').find('.btn-hide-panel-body').hasClass('hidden');
//        var semifinals = $('#semifinals').find('.btn-hide-panel-body').hasClass('hidden');
//        var final = $('#final').find('.btn-hide-panel-body').hasClass('hidden');

        setLocalItem(PANELS, {overviewHidden: overviewHidden,
            rulesHidden: rulesHidden,
            participantsHidden: participantsHidden});
    }
}

function renderOverview() {
    var panelsOverview = getLocalItem(PANELS);
    if (panelsOverview) {
        if (panelsOverview.overviewHidden === true) {
            $('#general-overview').find('.btn-hide-panel-body').click();
        }

        if (panelsOverview.rulesHidden === true) {
            $('#general-rules').find('.btn-hide-panel-body').click();
        }

        if (panelsOverview.participantsHidden === true) {
            $('#general-participants').find('.btn-hide-panel-body').click();
        }
    }
}

function renderUsers() {
    var users = getLocalItem(USERS);
    if (users && $('#general-participants .panel-body').children().length === 0) {
//        users = sortByKey(users, 'username');
        for (var user in users) {
//            if (parseInt(users[user].hasPayed) === 1) {
            var userItem = document.createElement('div');
            $(userItem).addClass('label label-default label-overview-user');
            $(userItem).append(document.createTextNode(users[user].username));
            $('#general-participants .panel-body').append(userItem);
//            }

        }
    }
}

function shouldPanelBeVisible(panelId) {
    var games = getLocalItem(GAMES);

    if (games) {
        var earliestDate = null;
        var oldestDate = null;
        var roundsFinished = true;

        for (var i = 0; i < games.length; i++) {

            if (panelId === games[i].round) {
                if (games[i].goalsParticipatorA === null && games[i].goalsParticipatorA === null &&
                        games[i].goalsParticipatorB === null && games[i].goalsParticipatorB === null) {
                    roundsFinished = false;
                }

                if (earliestDate === null || parseInt(games[i].timestamp) <= earliestDate) {
                    earliestDate = parseInt(games[i].timestamp);
                }
                if (oldestDate === null || parseInt(games[i].timestamp) >= oldestDate) {
                    oldestDate = parseInt(games[i].timestamp);
                }
            }
        }
    }

    var now = Date.now();
    var earliestVisibleDate = (earliestDate * 1000) + -2 * 24 * 3600 * 1000; // date 2 days befor the earliestDate in milliseconds 
    var oldestVisibleDate = (oldestDate * 1000) + 2 * 24 * 3600 * 1000; //date 2 days after the oldestDate in milliseconds

    if (!roundsFinished) {
        if (now >= earliestVisibleDate) {
            if (now >= earliestDate * 1000) {
                showGamesActive(panelId); // games startet now
            } else {
                var timeLeft = getTimeLeftForTimestamp(earliestDate * 1000);
                $('#' + panelId).find('.timer-text').removeClass('hidden');
                $('#' + panelId).find('.timer-text').text('noch ' + (timeLeft.days === 1 ? timeLeft.days + ' Tag, ' : timeLeft.days + ' Tage, ') + (timeLeft.hours === 1 ? timeLeft.hours + ' Stunde, ' : timeLeft.hours + ' Stunden, ') + (timeLeft.minutes === 1 ? timeLeft.minutes + ' Minute, ' : timeLeft.minutes + ' Minunten'));
                showGamesPreparation(panelId); // show panel-body, but games not started yet
            }
            return true;
        } else {
            var timeLeft = getTimeLeftForTimestamp(earliestDate * 1000);
            $('#' + panelId).find('.timer-text').removeClass('hidden');
            $('#' + panelId).find('.timer-text').text('noch ' + (timeLeft.days === 1 ? timeLeft.days + ' Tag, ' : timeLeft.days + ' Tage, ') + (timeLeft.hours === 1 ? timeLeft.hours + ' Stunde, ' : timeLeft.hours + ' Stunden, ') + (timeLeft.minutes === 1 ? timeLeft.minutes + ' Minute, ' : timeLeft.minutes + ' Minunten'));
            showGamesPreparation(panelId); // show panel-body, but games not started yet

            return false;
        }
    } else if (roundsFinished && now >= oldestVisibleDate) {
        showGamesFinished(panelId);
        return false;
    }

    showEMFinised(panelId); // em's finised
    return false;
}

function showGamesActive(panelId) {
    $('#' + panelId).find('.timer-text').addClass('hidden');
//    $('#' + panelId).find('.games-finished').addClass('hidden');
//    $('#' + panelId).find('.games-active').removeClass('hidden');
}

function showGamesFinished(panelId) {
    $('#' + panelId).find('.timer-text').addClass('hidden');
//    $('#' + panelId).find('.games-finished').removeClass('hidden');
//    $('#' + panelId).find('.games-active').addClass('hidden');
}

function showGamesPreparation(panelId) {
    $('#' + panelId).find('.timer-text').removeClass('hidden');
//    $('#' + panelId).find('.games-finished').addClass('hidden');
//    $('#' + panelId).find('.games-active').addClass('hidden');
}

function showEMFinised(panelId) {
    $('#' + panelId).find('.timer-text').addClass('hidden');
//    $('#' + panelId).find('.games-finished').addClass('hidden');
//    $('#' + panelId).find('.games-active').addClass('hidden');
}