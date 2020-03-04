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

.on("submit", "form.js-song", function (event) {
	event.preventDefault();

	var _form = $(this);
    var _error = $(".js-error", _form);
    var _display = $(".display");

    var _file = $('#file')[0].files[0];
    var _songTitle = $("#song-title", _form).val();
    var _artist = $("#artist", _form).val();
    var _album = $("#album", _form).val();

    var fd = new FormData();
    fd.append('file', _file);
    fd.append('song-title', _songTitle);
    fd.append('artist', _artist);
    fd.append('album', _album);

	// Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', 'ajax/song.php', fd, 'json', true, _error, true, _display)
        // Reset the form.
    $(':input',_form)
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        $(':submit', _form)
            .html('Upload Another');

})

.on("submit", "form.js-edit-profile", function (event) {
	event.preventDefault();

	var _form = $(this);
    var _error = $(".js-error", _form);
    var _display = $(".display");

    var _profileImage = $('#profile-image')[0].files[0];
    var _firstName = $("#first-name", _form).val();
    var _lastName = $("#last-name", _form).val();
    var _email = $("#email", _form).val();
    var _pass = $("#password", _form).val();
    var _birthDate = $("#birth-date", _form).val();
    var _bio = $("#bio", _form).val();
    

    var fd = new FormData();
    fd.append('profileImage', _profileImage);
    fd.append('firstName', _firstName);
    fd.append('lastName', _lastName);
    fd.append('email', _email);
    fd.append('password', _pass);
    fd.append('birthDate', _birthDate);
    fd.append('bio', _bio);

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

    sendAjax('POST', 'ajax/edit-profile.php', fd, 'json', true, _error, true, _display)
    

})

.on("submit", "form.js-image", function (event) {
	event.preventDefault();

	var _form = $(this);
    var _error = $(".js-error", _form);
    var _display = $(".display");

    var _file = $('#file')[0].files[0];
    var _imageTitle = $("#image-title", _form).val();

    var fd = new FormData();
    fd.append('file', _file);
    fd.append('image-title', _imageTitle);

	// Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', 'ajax/image.php', fd, 'json', true, _error, true, _display)
        // Reset the form.
    $(':input',_form)
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        $(':submit', _form)
            .html('Upload Another');

})

function sendAjax(requestType, requestUrl, dataobject, dType, asyncBool, _error, formdata = false, _display = null) {
    if (formdata) {
        $.ajax({
            type: requestType,
            url: requestUrl,
            data: dataobject,
            processData: false,
            contentType: false,
            async: asyncBool,
            error: function(xhr, status, error) {
                alert(xhr.responseText);
              }
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
    
            if (data.uploaded !== undefined) {
                _display
                    .html(data.uploaded)
                    .show();
            }

            if (data.image !== undefined) {
                $('.image-preview').attr('src', 'uploads/' + data.image);
            }

            if (data.success !== undefined) {
                _error
                    .html(data.success)
                    .show();
            }
        })
        .fail(function ajaxFailed(e){
            alert('Failed!!!')
            // Ajax call failed
            console.log(e);
        })
        .always(function ajaxAlwaysDoThis(data){
            // Always do
            console.log('Always');
        })
    
        return false;

    } else {

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

            if (data.uploaded !== undefined) {
                _playback
                    .html(data.uploaded);
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
}


