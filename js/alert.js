/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function appendAlert(target, alertType) {
    var children = $(target).find('.alert-' + alertType).find('#' + alertType);
    
    if (children.length === 0) {
        var alert = $('#alert-container').find('#' + alertType).clone();
        $(target).find('.alert-' + alert.attr('id')).append(alert);
    }
}

function removeAlert(target, alertType) {
    $(target).find('.alert-' + alertType).empty();
}

function clearAlerts(target) {
    $(target).find('.alert-space').children().remove();
}