var currentList;
function renderToplist() {
    var tips = getLocalItem(TIPS);
//    var thereResults = false;

//    var games = getLocalItem(GAMES);
//    for (var i = 0; i < games.length; i++) {
//        if (games[i].goalsParticipatorA !== null && games[i].goalsParticipatorB !== null) {
    $('#panel-top-list').removeClass('hidden');
//            thereResults = true;
//            break;
//        }
//    }
//    
//    if (!thereResults) {
//        appendAlert($('#main-content'), ALERT_NO_RESULTS);
//    }

    $('#btn-toggle-point-summary').unbind('click').bind('click', function (event) {
        event.preventDefault();
        if ($('#point-summary').hasClass('hidden')) {
            $(this).find('.fa').removeClass('fa-plus-circle').addClass('fa-minus-circle');
            $('#point-summary').removeClass('hidden');
        } else {
            $(this).find('.fa').removeClass('fa-minus-circle').addClass('fa-plus-circle');
            $('#point-summary').addClass('hidden');
        }
    });

    var userPoints = [];
    var users = getLocalItem(USERS);
    if (users)
    {
        for (var i = 0; i < users.length; i++) {
            userPoints.push({userId: users[i].id, username: users[i].username, points: calculateFinalPoints(users[i].id), place: null});
            for (var j = 0; j < tips.length; j++) {
                if (tips[j].userId === users[i].id) {
//                    console.log(hasTippedWinnerCorrectly(tips[j]));
                    var winnerTippedPoints = hasTippedWinnerCorrectly(tips[j]) === true ? CORRECT_WINNER_TIP_POINTS : 0;
//                    console.log(winnerTippedPoints);
                    var points = calculatePoints(tips[j]);
                    userPoints[i].points = userPoints[i].points + points + winnerTippedPoints;
                }
            }
        }
    }

    renderList(userPoints, true);
    renderTips(userPoints);
    console.log($(window).width());
    if ($(window).width() > 768) {
        renderChart();
    }
}

function renderList(list, bestFirst) {
    currentList = sortByKey(list, 'points', bestFirst);
    var myUserId = getLocalItem('userId');
    $('#table-list').empty();
    var currentPoints = null;
    var currentIndex = null;
    var sameScore = false;
    var tableRow;
    var tableDataPlacement;
    var tableDataUsernames;
    var tableDataPoints;
    var totalDelay = 0.1;
    CSSPlugin.useSVGTransformAttr = true;

    for (var i = 0; i < currentList.length; i++) {

        if (currentList[i].points !== currentPoints) {
            currentPoints = currentList[i].points;
            sameScore = false;
        } else {
            sameScore = true;
        }

        if (!sameScore) {
            tableRow = document.createElement('tr');
            $('#table-list').append(tableRow);
            $(tableRow).addClass('tableRow');
            TweenMax.from(tableRow, .3, {x: -30, opacity: 0, ease: Elastic.easeOut, delay: totalDelay + (i * 0.05)});

//            $(tableRow).on('mouseenter', function (event) {
//                event.preventDefault();
//                TweenMax.from($(this), .6, {x: -30, ease: Elastic.easeOut});
//            });
        }

        if (!sameScore)
        {
            if (bestFirst) {
                currentIndex = i + 1;
                if (currentList[i].place === null) {
                    currentList[i].place = currentIndex;
                }
            } else {
                currentIndex = currentList[i].place;
            }

            tableDataPlacement = document.createElement('td');
            $(tableDataPlacement).text(currentIndex);
            $(tableDataPlacement).addClass('placement');
            $(tableRow).append(tableDataPlacement);

            tableDataUsernames = document.createElement('td');
            $(tableDataUsernames).addClass('usernames');
            var username = document.createElement('span');
            username.appendChild(document.createTextNode(parseInt(myUserId) === currentList[i].userId ? " " + currentList[i].username : currentList[i].username));
            $(tableDataUsernames).append(username);
            $(tableRow).append(tableDataUsernames);

            tableDataPoints = document.createElement('td');
            $(tableDataPoints).addClass('points');
            $(tableDataPoints).text(currentList[i].points);
            $(tableRow).append(tableDataPoints);


        } else {
//            $(tableRow).find('.usernames').text($(tableRow).find('.usernames').text() + ", " + currentList[i].username);
            var username = document.createElement('span');
            username.appendChild(document.createTextNode(", " + currentList[i].username));
            $(tableDataUsernames).append(username);
//            username.appendChild(document.createTextNode(currentList[i].username));
        }

        if (parseInt(myUserId) === currentList[i].userId) {
            $(tableDataPlacement).addClass('my-score');
            $(username).addClass('my-score');
            var userSign = document.createElement('i');
            $(userSign).addClass('glyphicon glyphicon-user');
            $(username).prepend(userSign);
            $(tableDataPoints).addClass('my-score');
            $(tableDataUsernames).addClass('my-score-green-bg');
        }
    }
}

var MAX_LAST_GAMES = 6;
function renderTips(list) {
    var currentUserList = sortByKey(list, 'points', true);
    var tips = getLocalItem(TIPS);
    var finishedGames = getLocalItem(GAMES).reverse();
//    var finishedGames = getTippedGames();
//    finishedGames = sortByKey(finishedGames, 'id', true);

    var myUserId = getLocalItem('userId');
    $('#panel-tipp-list').removeClass('hidden');
    $('#panel-tipp-list').closest('.panel').find('.panel-body').addClass('hidden');

    var currentUsername;
    var tableRow;
    var tableDataUsername;
    var tableDataResult;
    var tableDataTipp;
    var tableDataPoints;

    var maxItems = finishedGames.length < MAX_LAST_GAMES ? finishedGames.length : MAX_LAST_GAMES;
//    var totalDelay = 1;

    for (var i = 0; i < currentUserList.length; i++) {
        for (var j = 0; j < maxItems; j++) {
            var userTip = getUserTipForGame(currentUserList[i].userId, finishedGames[j].id);

            tableRow = document.createElement('tr');
            $(tableRow).addClass('tableRow');
            $(tableRow).attr('id', 'user' + currentUserList[i].userId);
            $('#tipp-list').append(tableRow);

//            $(tableRow).on('mouseenter', function (event) {
//                event.preventDefault();
//                TweenMax.from($(this), .6, {x: -30, ease: Elastic.easeOut});
//            });

            tableDataUsername = document.createElement('td');
            $(tableDataUsername).addClass('username');
            $(tableRow).append(tableDataUsername);

            tableDataTipp = document.createElement('td');
            $(tableDataTipp).addClass('tipp text-center');
            $(tableRow).append(tableDataTipp);

            tableDataResult = document.createElement('td');
            $(tableDataResult).addClass('result text-center');
            $(tableRow).append(tableDataResult);

            tableDataPoints = document.createElement('td');
            $(tableDataPoints).addClass('points text-center');
            $(tableRow).append(tableDataPoints);

            var username = document.createElement('span');


            if (parseInt(myUserId) === currentUserList[i].userId) {
                $(tableRow).addClass('my-score-green-bg');
                $(tableDataUsername).addClass('my-score');

                if (j === 0) {
                    var userSign = document.createElement('i');
                    $(userSign).addClass('glyphicon glyphicon-user');
                    $(tableDataUsername).append(userSign);
                    username.appendChild(document.createTextNode(' ' + currentUserList[i].username));
                    $(tableDataUsername).append(username);
                }
            } else {
                if (j === 0) {
                    $(tableDataUsername).append(username);
                    username.appendChild(document.createTextNode(' ' + currentUserList[i].username));
                    $(tableDataUsername).append(document.createElement('br'));

                    var eye = document.createElement('button');
                    $(eye).addClass('btn btn-xs btn-default show-user-tips');
                    $(eye).css('margin-top', '4px');
                    var icon = document.createElement('i');
                    $(icon).addClass('glyphicon glyphicon-plus-sign');
                    $(eye).append(icon);
                    var text = document.createElement('span');
                    $(text).addClass('collapse-text');
                    $(text).text(' mehr');
                    $(eye).append(text);
                    $(tableDataUsername).append(eye);

                    $(eye).on('click', function (event) {
                        event.preventDefault();

                        var id = $(this).closest('.tableRow').attr('id');
                        if ($(this).hasClass('show-user-tips')) {
                            $(this).removeClass('show-user-tips');
                            $(this).addClass('hide-user-tips');
                            $(this).find('.collapse-text').text(' weniger');
                            $(this).find('.glyphicon').removeClass('glyphicon-plus-sign');
                            $(this).find('.glyphicon').addClass('glyphicon-minus-sign');
                            $(this).closest('.tableRow').parent().children('#' + id + '-collapsable').removeClass('hidden');
                        } else {
                            $(this).removeClass('hide-user-tips');
                            $(this).addClass('show-user-tips');
                            $(this).find('.collapse-text').text(' mehr');
                            $(this).find('.glyphicon').addClass('glyphicon-plus-sign');
                            $(this).find('.glyphicon').removeClass('glyphicon-minus-sign');
                            $(this).closest('.tableRow').parent().children('#' + id + '-collapsable').addClass('hidden');
                        }
                    });
                } else {
                    $(tableRow).addClass('hidden');
                    $(tableRow).attr('id', 'user' + currentUserList[i].userId + '-collapsable');
                }
            }

            var flagParticipatorA = document.createElement('img');
            $(flagParticipatorA).css('width', '20px');
            $(flagParticipatorA).attr('src', FLAG_IMAGE_PATH + finishedGames[j].participatorA + '.png');
            $(flagParticipatorA).attr('alt', getCountryByIso(finishedGames[j].participatorA).name);

            var flagParticipatorB = document.createElement('img');
            $(flagParticipatorB).css('width', '20px');
            $(flagParticipatorB).attr('src', FLAG_IMAGE_PATH + finishedGames[j].participatorB + '.png');
            $(flagParticipatorB).attr('alt', getCountryByIso(finishedGames[j].participatorB).name);

            $(tableDataResult).append(flagParticipatorA);
            $(tableDataResult).append(document.createTextNode('  '));
            $(tableDataResult).append(flagParticipatorB);
            $(tableDataResult).append(document.createElement('br'));

            if (finishedGames[j].goalsParticipatorA !== null && finishedGames[j].goalsParticipatorB !== null) {
                if (finishedGames[j].goalsPenaltyParticipatorA !== null && finishedGames[j].goalsPenaltyParticipatorB !== null) {
                    $(tableDataResult).append(document.createTextNode(finishedGames[j].goalsParticipatorA + '(' + finishedGames[j].goalsPenaltyParticipatorA + ') : ' + finishedGames[j].goalsParticipatorB + '(' + finishedGames[j].goalsPenaltyParticipatorB + ')'));
                } else {
                    $(tableDataResult).append(document.createTextNode(finishedGames[j].goalsParticipatorA + ' : ' + finishedGames[j].goalsParticipatorB));
                }
            } else {
                $(tableDataResult).append(document.createTextNode('- : -'));
            }


            if (userTip !== null && finishedGames[j].goalsParticipatorA !== null && finishedGames[j].goalsParticipatorB !== null) {
                tableDataTipp.appendChild(document.createTextNode(userTip.goalsParticipatorA + ' : ' + userTip.goalsParticipatorB));

                var winnerTippedPoints = hasTippedWinnerCorrectly(userTip) === true ? CORRECT_WINNER_TIP_POINTS : 0;
                var tippedPoints = calculatePoints(userTip);

                var pointsTipp = document.createElement('span');
                $(pointsTipp).addClass('badge');
                pointsTipp.appendChild(document.createTextNode(tippedPoints + " + " + winnerTippedPoints));
                $(tableDataPoints).append(pointsTipp);
            } else if (userTip !== null && finishedGames[j].goalsParticipatorA === null && finishedGames[j].goalsParticipatorB === null) {
                tableDataTipp.appendChild(document.createTextNode(userTip.goalsParticipatorA + ' : ' + userTip.goalsParticipatorB));

                var pointsTipp = document.createElement('span');
                $(pointsTipp).addClass('badge');
                pointsTipp.appendChild(document.createTextNode('Warten'));
                $(tableDataPoints).append(pointsTipp);
            } else {
                tableDataTipp.appendChild(document.createTextNode('Kein Tipp'));

                var pointsTipp = document.createElement('span');
                $(pointsTipp).addClass('badge');
                pointsTipp.appendChild(document.createTextNode('0'));
                $(tableDataPoints).append(pointsTipp);
            }
        }
    }

    return false;

    for (var i = 0; i < currentUserList.length; i++) {
        for (var j = 0; j < finishedGames.length; j++) {
            var userTip = getUserTipForGame(currentUserList[i].userId, finishedGames[j].id);
            if (userTip !== null) {
                tableRow = document.createElement('tr');
                $(tableRow).addClass('tableRow');
                $(tableRow).attr('id', 'user' + currentUserList[i].userId);
                $('#tipp-list').append(tableRow);

                tableDataUsername = document.createElement('td');
                $(tableDataUsername).addClass('username');
                $(tableRow).append(tableDataUsername);

                tableDataTipp = document.createElement('td');
                $(tableDataTipp).addClass('tipp text-center');
                $(tableRow).append(tableDataTipp);

                tableDataResult = document.createElement('td');
                $(tableDataResult).addClass('result text-center');
                $(tableRow).append(tableDataResult);

                tableDataPoints = document.createElement('td');
                $(tableDataPoints).addClass('points text-center');
                $(tableRow).append(tableDataPoints);

                var username = document.createElement('span');

                if (j === 0) {
                    if (parseInt(myUserId) === userTip.userId) {
                        $(tableRow).addClass('my-score-green-bg');
                        $(tableDataUsername).addClass('my-score');

                        var userSign = document.createElement('i');
                        $(userSign).addClass('glyphicon glyphicon-user');
                        $(tableDataUsername).append(userSign);
                        username.appendChild(document.createTextNode(' ' + currentUserList[i].username));
                        $(tableDataUsername).append(username);

                    } else {
                        $(tableDataUsername).append(username);
                        username.appendChild(document.createTextNode(' ' + currentUserList[i].username));
                        $(tableDataUsername).append(document.createElement('br'));

                        var eye = document.createElement('button');
                        $(eye).addClass('btn btn-xs btn-default show-user-tips');
                        $(eye).css('margin-top', '4px');
                        var icon = document.createElement('i');
                        $(icon).addClass('glyphicon glyphicon-plus-sign');
                        $(eye).append(icon);
                        var text = document.createElement('span');
                        $(text).addClass('collapse-text');
                        $(text).text(' mehr');
                        $(eye).append(text);
                        $(tableDataUsername).append(eye);

                        $(eye).on('click', function (event) {
                            event.preventDefault();

                            var id = $(this).closest('.tableRow').attr('id');
//                            console.log($(this).closest('.tableRow').parent().children('#' + id));
                            if ($(this).hasClass('show-user-tips')) {
                                $(this).removeClass('show-user-tips');
                                $(this).addClass('hide-user-tips');
                                $(this).find('.collapse-text').text(' weniger');
                                $(this).find('.glyphicon').removeClass('glyphicon-plus-sign');
                                $(this).find('.glyphicon').addClass('glyphicon-minus-sign');
                                $(this).closest('.tableRow').parent().children('#' + id + '-collapsable').removeClass('hidden');
                            } else {
                                $(this).removeClass('hide-user-tips');
                                $(this).addClass('show-user-tips');
                                $(this).find('.collapse-text').text(' mehr');
                                $(this).find('.glyphicon').addClass('glyphicon-plus-sign');
                                $(this).find('.glyphicon').removeClass('glyphicon-minus-sign');
                                $(this).closest('.tableRow').parent().children('#' + id + '-collapsable').addClass('hidden');
                            }
                        });
                    }
                } else if (j > 0 && userTip.userId !== parseInt(myUserId)) {
                    $(tableRow).addClass('hidden');
                }

                if (j > 0) {
                    $(tableRow).attr('id', 'user' + currentUserList[i].userId + '-collapsable');
                }



                tableDataTipp.appendChild(document.createTextNode(userTip.goalsParticipatorA + ' : ' + userTip.goalsParticipatorB));

                var flagParticipatorA = document.createElement('img');
                $(flagParticipatorA).css('width', '20px');
                $(flagParticipatorA).attr('src', FLAG_IMAGE_PATH + finishedGames[j].participatorA + '.png');
                $(flagParticipatorA).attr('alt', getCountryByIso(finishedGames[j].participatorA).name);

                var flagParticipatorB = document.createElement('img');
                $(flagParticipatorB).css('width', '20px');
                $(flagParticipatorB).attr('src', FLAG_IMAGE_PATH + finishedGames[j].participatorB + '.png');
                $(flagParticipatorB).attr('alt', getCountryByIso(finishedGames[j].participatorB).name);

                $(tableDataResult).append(flagParticipatorA);
                $(tableDataResult).append(document.createTextNode('  '));
                $(tableDataResult).append(flagParticipatorB);
                $(tableDataResult).append(document.createElement('br'));

                $(tableDataResult).append(document.createTextNode(finishedGames[j].goalsParticipatorA + ' : ' + finishedGames[j].goalsParticipatorB));

                var winnerTippedPoints = hasTippedWinnerCorrectly(userTip) === true ? CORRECT_WINNER_TIP_POINTS : 0;
                var tippedPoints = calculatePoints(userTip);

                var pointsTipp = document.createElement('span');
                $(pointsTipp).addClass('badge');
                pointsTipp.appendChild(document.createTextNode(tippedPoints + " + " + winnerTippedPoints));
                $(tableDataPoints).append(pointsTipp);
            }
        }
    }
    return false;

    for (var i = 0; i < currentUserList.length; i++) {
        for (var j = 0; j < tips.length; j++) {
            if (tips[j].userId === currentUserList[i].userId) {

                tableRow = document.createElement('tr');

                tableDataUsername = document.createElement('td');
                $(tableDataUsername).addClass('username');
                $(tableRow).append(tableDataUsername);

                tableDataTipp = document.createElement('td');
                $(tableDataTipp).addClass('tipp text-center');
                $(tableRow).append(tableDataTipp);

                tableDataResult = document.createElement('td');
                $(tableDataResult).addClass('result text-center');
                $(tableRow).append(tableDataResult);

                tableDataPoints = document.createElement('td');
                $(tableDataPoints).addClass('points text-center');
                $(tableRow).append(tableDataPoints);

                if (parseInt(myUserId) === tips[j].userId) {
                    $(tableRow).addClass('my-score-green-bg');
                    $(tableDataUsername).addClass('my-score');
                }

                if (currentUserList[i].username !== currentUsername) {
                    currentUsername = currentUserList[i].username;
                    var username = document.createElement('span');

                    if (parseInt(myUserId) === tips[j].userId) {
                        var userSign = document.createElement('i');
                        $(userSign).addClass('glyphicon glyphicon-user');
                        $(tableDataUsername).append(userSign);
                        username.appendChild(document.createTextNode(' ' + currentUserList[i].username));
                    } else {
                        username.appendChild(document.createTextNode(currentUserList[i].username));
                    }
                    $(tableDataUsername).append(username);
                } else if (parseInt(myUserId) !== tips[j].userId) {
//                    $(tableRow).addClass('collapse');
                }

                var game = getGameById(tips[j].gameId);
                if (game && game.goalsParticipatorA !== null && game.goalsParticipatorB !== null) {
                    $('#tipp-list').append(tableRow);

                    tableDataTipp.appendChild(document.createTextNode(tips[j].goalsParticipatorA + ' : ' + tips[j].goalsParticipatorB));

                    var flagParticipatorA = document.createElement('img');
                    $(flagParticipatorA).css('width', '20px');
                    $(flagParticipatorA).attr('src', FLAG_IMAGE_PATH + game.participatorA + '.png');
                    $(flagParticipatorA).attr('alt', getCountryByIso(game.participatorA).name);

                    var flagParticipatorB = document.createElement('img');
                    $(flagParticipatorB).css('width', '20px');
                    $(flagParticipatorB).attr('src', FLAG_IMAGE_PATH + game.participatorB + '.png');
                    $(flagParticipatorB).attr('alt', getCountryByIso(game.participatorB).name);

                    $(tableDataResult).append(flagParticipatorA);
                    $(tableDataResult).append(document.createTextNode('  '));
                    $(tableDataResult).append(flagParticipatorB);
                    $(tableDataResult).append(document.createElement('br'));

                    $(tableDataResult).append(document.createTextNode(game.goalsParticipatorA + ' : ' + game.goalsParticipatorB));

                    var winnerTippedPoints = hasTippedWinnerCorrectly(tips[j]) === true ? CORRECT_WINNER_TIP_POINTS : 0;
                    var tippedPoints = calculatePoints(tips[j]);

                    var pointsTipp = document.createElement('span');
                    $(pointsTipp).addClass('badge');
                    pointsTipp.appendChild(document.createTextNode(tippedPoints + " + " + winnerTippedPoints));
                    $(tableDataPoints).append(pointsTipp);
                }
            }
        }
    }
}

function getUserTipForGame(userId, gameId) {
    var tips = getLocalItem(TIPS);
    for (var i = 0; i < tips.length; i++) {
        if (tips[i].userId === userId && tips[i].gameId === gameId) {
            return tips[i];
        }
    }
    return null;
}

function renderChart() {
    var chartData = [];
    var games = getLocalItem(GAMES);
//    var tips = ;
    var users = getLocalItem(USERS);

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

    if (chartData && chartData.length > 0) {
        var colors = randomColor({luminosity: 'dark', count: chartData.length});
        var datasets = [];
        var labels = [];
        var startIndex = Math.max(0, chartData[0].points.length - 12);


        for (var i = 0; i < chartData.length; i++) {
            var data = [];
            for (var j = startIndex; j < chartData[i].points.length; j++) {
                data.push(chartData[i].points[j].sum);
                if (i === 0) {
                    var game = getGameById(chartData[i].points[j].gameId);
                    labels.push(getCountryByIso(game.participatorA).name + ' : ' + getCountryByIso(game.participatorB).name);
                }
            }

            datasets.push({label: chartData[i].username, data: data, borderColor: colors[i], borderWidth: 3, fill: false, lineTension: .2});
        }
        console.log(startIndex, chartData[0].points.length, datasets, labels);

        $('#static-chart-container').removeClass('hidden');
        var ctx = document.getElementById("static-chart").getContext('2d');
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
                                top: 10
                            }
                        }]
                }
            }
        });
    }
}