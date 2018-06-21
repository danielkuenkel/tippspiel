var timer = null;
var tipSubmitted = false;

var excreted = ['ru', 'ro', 'ua', 'cz', 'at', 'tr', 'se', 'al'];

function renderWinnerTips() {
    var countries = getLocalItem(COUNTRIES);
    var winnerTip = getLocalItem(WINNER_TIP);

    var timeLeft = isTimeLeft(POSSIBLE_WINNER_TIP_DATE);

    if (timeLeft && countries && countries.length > 0) {
        $('#counter-time-header').removeClass('hidden');
        $('#counter-time').removeClass('hidden');
        var timeLeft = getTimeLeftForTimestamp(POSSIBLE_WINNER_TIP_DATE);
        $('#counter-time').text(timeLeft.days + ' Tage, ' + timeLeft.hours + ' Stunden, ' + timeLeft.minutes + ' Minunten und ' + timeLeft.seconds + ' Sekunden');


        if (tipSubmitted || $('#country-list').children().length === 0) {
            tipSubmitted = false;

            for (var i = 0; i < countries.length; i++) {
                var containerItem;

                if (isInLastSixteen(countries[i].iso)) {
                    
                    if ($('#country-list').find('#' + countries[i].iso).length !== 0) {
                        containerItem = $('#country-list').find('#' + countries[i].iso);
                    } else {
                        containerItem = $('#country-container-item').clone().removeClass('hidden').removeAttr('id');
                        containerItem.attr('id', countries[i].iso);
                        containerItem.find('.country-name').text(countries[i].name);
                        containerItem.find('.flag').attr('src', FLAG_IMAGE_PATH + countries[i].iso + '.png');
                        
                        $('#country-list').append(containerItem);
                    }
                    unlockButton(containerItem, true, 'fa-heart');

                    if (timeLeft) {
                        containerItem.find('.btn-submit-tip').unbind('click').bind('click', function (event) {
                            event.preventDefault();
                            $(this).addClass('disabled');
                            lockButton($(this), true, 'fa-heart');
                            clearTimeout(timer);
                            tipSubmitted = true;
                            var countryIso = $(this).closest('.country-item').attr('id');
                            submitWinnerTip({countryIso: countryIso});
                        });

                        if (countries[i].iso === winnerTip[0].iso) {
                            containerItem.addClass('country-item-active');
                            containerItem.find('.user-tip-winner').removeClass('hidden');
                            containerItem.find('.btn-submit-tip').addClass('hidden');
                        } else {
                            containerItem.removeClass('country-item-active');
                            containerItem.find('.user-tip-winner').addClass('hidden');
                            containerItem.find('.btn-submit-tip').removeClass('hidden');
                            containerItem.find('.btn-submit-tip').removeClass('disabled');
                        }
                    } else {
                        containerItem.find('.btn-submit-tip').addClass('hidden');
                    }
                }
            }
        }
    } else {
        var tippedCountry = getCountryByIso(winnerTip[0].iso);

        if (tippedCountry) {
            var containerItem = $('#country-container-item').clone().removeClass('hidden').removeAttr('id');
            containerItem.attr('id', tippedCountry.iso);
            containerItem.find('.country-name').text(tippedCountry.name);
            containerItem.find('.flag').attr('src', FLAG_IMAGE_PATH + tippedCountry.iso + '.png');
            containerItem.addClass('country-item-active');
            containerItem.find('.user-tip-winner').removeClass('hidden');
            containerItem.find('.btn-submit-tip').addClass('hidden');
            $('#country-list').append(containerItem);
            $('#times-up').removeClass('hidden');
        } else {
            $('#no-tip-submitted').removeClass('hidden');
        }
    }

    clearTimeout(timer);
    if (timeLeft) {
        timer = setTimeout(renderWinnerTips, 1000);
    }

}

function isInLastSixteen(iso) {
    for (var i = 0; i < excreted.length; i++) {
        if (excreted[i] === iso) {
            return false;
        }
    }
    return true;
}
                    