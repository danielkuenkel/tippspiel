function formhash(form) {
    // Create a new element input, this will be our hashed password field. 
//    alert('submit form');
    var email = $(form).find('#email');
    var password = $(form).find('#password');
    
    if ($(email).val().trim() === '' || $(password).val().trim() === '') {
        appendAlert(form, ALERT_MISSING_FIELDS);
        return false;
    }
    if ($(email).val().trim() === '' || $(password).val().trim() === '') {

        return false;
    }

    // Add the new element to our form. 
    if ($(form).find('#p').length > 0) {
        $(form).find('#p').val(hex_sha512(password.val()));
    } else {
        var p = document.createElement("input");
        $(form).append(p);
        $(p).attr('id', 'p');
        $(p).attr('name', 'p');
        $(p).attr('type', 'hidden');
        $(p).val(hex_sha512(password.val()));
    }

    // Make sure the plaintext password doesn't get sent. 
    password.val("");

    // Finally submit the form. 
    form.submit();
}

function forgotFormhash(form) {
    clearAlerts(form);
    var email = $(form).find('#email');
    if ($(email).val().trim() === '') {
        appendAlert(form, ALERT_MISSING_EMAIL);
        $(email).focus();
        return false;
    }

    // validate email
    if (!validateEmail($(email).val().trim())) {
        $(email).focus();
        return false;
    }

    form.submit();
}

function registerFormhash(form) {
    var username = $(form).find('#username');
    var email = $(form).find('#email');
    var password = $(form).find('#password');
    var passwordconfirm = $(form).find('#confirmPassword');

    // Check each field has a value
    if ($(username).val().trim() === '' ||
            $(email).val().trim() === '' ||
            $(password).val().trim() === '' ||
            $(passwordconfirm).val().trim() === '')
    {
        appendAlert(form, ALERT_MISSING_FIELDS);
        return false;
    }

    // validate email
    if (!validateEmail($(email).val().trim())) {
        $(email).focus();
        appendAlert(form, ALERT_INVALID_EMAIL);
        return false;
    }

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if ($(password).val().trim().length < 6) {
        appendAlert(form, ALERT_PASSWORD_SHORT);
        $(password).focus();
        return false;
    }

    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 

    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if (!re.test($(password).val().trim())) {
        appendAlert(form, ALERT_PASSWORD_INVALID);
        return false;
    }
    
    // Check password and confirmation are the same
    if ($(password).val() !== $(passwordconfirm).val()) {
        $(passwordconfirm).focus();
        appendAlert(form, ALERT_PASSWORDS_NOT_MATCHING);
        return false;
    }

    if ($(form).find('#p').length > 0) {
        $(form).find('#p').val(hex_sha512(password.val()));
    } else {
        var p = document.createElement("input");
        $(form).append(p);
        $(p).attr('id', 'p');
        $(p).attr('name', 'p');
        $(p).attr('type', 'hidden');
        $(p).val(hex_sha512(password.val()));
    }

    // Make sure the plaintext password doesn't get sent. 
    password.val("");

    // Finally submit the form. 
    form.submit();
}


function resetPasswordFormhash(form) {
    // Check each field has a value

    var email = $(form).find('#email');
    var password = $(form).find('#password');
    var passwordconfirm = $(form).find('#confirmPassword');

    if ($(email).val().trim() === '' ||
            $(password).val().trim() === '' ||
            $(passwordconfirm).val().trim() === '') {
        appendAlert(form, ALERT_MISSING_FIELDS);
        return false;
    }

    // validate email
    if (!validateEmail($(email).val().trim())) {
        $(email).focus();
        console.log('append alert, ', form);
        appendAlert(form, ALERT_INVALID_EMAIL);
        return false;
    }

    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if ($(password).val().length < 6) {
        $(password).focus();
        appendAlert(form, ALERT_PASSWORD_SHORT);
        return false;
    }

    // At least one number, one lowercase and one uppercase letter 
    // At least six characters 
    var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
    if (!re.test($(password).val())) {
        $(password).focus();
        appendAlert(form, ALERT_PASSWORD_INVALID);
        return false;
    }

    // Check password and confirmation are the same
    if ($(password).val() !== $(passwordconfirm).val()) {
        $(passwordconfirm).focus();
        appendAlert(form, ALERT_PASSWORDS_NOT_MATCHING);
        return false;
    }

    // Add the new element to our form. 
//    var passwordString = password.val() + '-' + $(email).val();
    if ($(form).find('#p').length > 0) {
        $(form).find('#p').val(hex_sha512(password.val()));
    } else {
        var p = document.createElement("input");
        $(form).append(p);
        $(p).attr('id', 'p');
        $(p).attr('name', 'p');
        $(p).attr('type', 'hidden');
        $(p).val(hex_sha512(password.val()));
    }

    // Make sure the plaintext password doesn't get sent. 
    $(password).val('');
    $(passwordconfirm).val('');

    // Finally submit the form. 
    form.submit();
}

function validateEmail(email) {
    var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
    return re.test(email);
}