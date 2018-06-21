
function renderGrouplist() {
    var countries = getLocalItem(COUNTRIES);
    if (countries && countries.length > 0) {
        var currentGroup = null;
        var listItem = null;
        for (var i = 0; i < countries.length; i++) {
            
            if(countries[i].group !== currentGroup) {
                currentGroup = countries[i].group;
                listItem = $('#group-list-item').clone().removeClass('hidden').removeAttr('id');
                listItem.attr('id', 'group-' + currentGroup);
                listItem.find('.group-number').text(currentGroup);
                $('#group-list').append(listItem);
            }
            
            var containerItem = $('#country-container-item').clone().removeClass('hidden').removeAttr('id');
            containerItem.attr('id', countries[i].iso);
            containerItem.find('.country-name').text(countries[i].name);
            containerItem.find('.flag').attr('src', FLAG_IMAGE_PATH + countries[i].iso + '.png');
            listItem.find('.country-container').append(containerItem);
        }
    }
}