$(document)
.on("submit", "form.js-register", function (event) {
	event.preventDefault();

	var _form = $(this);
	var _error = $(".js-error", _form);

	var dataObj = {
        firstName: $('#first-name', _form).val(),
        lastName: $('#last-name', _form).val(),
		email: $("input[type='email']", _form).val(),
        password: $("input[type='password']", _form).val(),
	};

	if(dataObj.email.length < 6) {
		_error
			.html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Please enter a valid email address</p>" +
          "</div>")
			.show();
		return false;
	} else if (dataObj.password.length < 11) {
		_error
            .html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Your password must be at least 11 characters.</p>" +
          "</div>")
			.show();
		return false;
	}

	// Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', 'ajax/register.php', dataObj, 'json', true, _error);
})

.on("submit", "form.js-login", function (event) {
	event.preventDefault();

	var _form = $(this);
	var _error = $(".js-error", _form);

	var dataObj = {
		email: $("input[type='email']", _form).val(),
        password: $("input[type='password']", _form).val(),
    };
    
	if(dataObj.email.length < 6) {
		_error
			.html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Please enter a valid email addres.</p>" +
          "</div>")
			.show();
		return false;
	} else if (dataObj.password.length < 11) {
		_error
			.html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Please enter a password that is at least 11 characters long.</p>" +
          "</div")
			.show();
		return false;
	}

	// Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', 'ajax/login.php', dataObj, 'json',true, _error);
})

function sendAjax(requestType, requestUrl, dataobject, dType, asyncBool, _error) {
    $.ajax({
        type: requestType,
        url: requestUrl,
        data: dataobject,
        dataType: dType,
        async: asyncBool,
    })
    .done(function ajaxDone(data) {
        // Whatever the dataObj is
        console.log(data);
        if (data.error !== undefined) {
            _error
                .html(data.error)
                .show();
        } else if (data.redirect !== undefined) {
          window.location = data.redirect;
        } 
    })
    .fail(function ajaxFailed(e){
        // Ajax call failed
        console.log(e);
    })
    .always(function ajaxAlwaysDoThis(data){
        // Always do
        console.log('Always');
    })

	return false;
}
