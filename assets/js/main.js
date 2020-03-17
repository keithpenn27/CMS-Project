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

    sendAjax('POST', '../ajax/register.php', dataObj, 'json', true, _error);
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

    sendAjax('POST', '../ajax/login.php', dataObj, 'json',true, _error);
})

.on("submit", "form.js-post-add", function (event) {
    event.preventDefault();

    var _form = $(this);
    var _error = $(".js-error", _form);

    var dataObj = {
        postTitle: $("#post-title", _form).val(),
        postContent: $("#post-content", _form).val(),
    };
    
    if(dataObj.postTitle == "") {
        _error
            .html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Please enter a title for your post.</p>" +
          "</div>")
            .show();
        return false;
    }

    // Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', '../ajax/post-add.php', dataObj, 'json',true, _error);
})

.on("submit", "form.js-post-edit", function (event) {
    event.preventDefault();

    var split = location.search.replace('?', '').split('=');
    var getVal = split[1];

    var _form = $(this);
    var _error = $(".js-error", _form);

    var dataObj = {
        postTitle: $("#post-title", _form).val(),
        postContent: $("#post-content", _form).val(),
        getVal: getVal
    };
    
    if(dataObj.postTitle == "") {
        _error
            .html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Please enter a title for your post.</p>" +
          "</div>")
            .show();
        return false;
    }

    // Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', '../ajax/post-edit.php', dataObj, 'json',true, _error);
})

.on("click", "a.delete-file", function (event) {
    event.preventDefault();

    var _error = $(".js-error");

    var dataObj = {
        fileName: $("a.delete-file").data("file-name"),
        fileId: $("a.delete-file").data("file-id")
    };

    sendAjax('POST', '../ajax/delete-file.php', dataObj, 'json',true, _error);

    $(this).parent().fadeOut(600, function(){
        $(this).html("The file was deleted.").fadeIn(400, function() {
            $(this).delay(1000).fadeOut(600, "")
        });
    })

})

.on("submit", "form.js-song", function (event) {
	event.preventDefault();

	var _form = $(this);
    var _error = $(".js-error", _form);
    var _display = $(".display");

    var _file = $('#song-upload')[0].files[0];
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

    sendAjax('POST', '../ajax/song.php', fd, 'json', true, _error, true, _display)
        // Reset the form.
    $(':input',_form)
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        $(':submit', _form)
            .html('Upload Another');

    $('.display').delay(1000).fadeOut(600);

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
    
    console.log(dataObj.location);
	if(dataObj.email.length < 6) {
		_error
			.html("<div class='alert alert-dismissible alert-warning'>" +
            "<button type='button' class='close' data-dismiss='alert';>&times;</button>" +
            "<h4 class='alert-heading'>Warning!</h4>" +
            "<p class='mb-0'>Please enter a valid email addres.</p>" +
          "</div>")
			.show();
		return false;
	} else if ((dataObj.password != '') && dataObj.password.length < 11 ) {
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

    sendAjax('POST', '../ajax/edit-profile.php', fd, 'json', true, _error, true, _display);
    

})

.on("submit", "form.js-image", function (event) {
	event.preventDefault();

	var _form = $(this);
    var _error = $(".js-error", _form);
    var _display = $(".display");

    var _file = $('#image-upload')[0].files[0];
    var _imageTitle = $("#image-title", _form).val();

    var fd = new FormData();
    fd.append('file', _file);
    fd.append('image-title', _imageTitle);

	// Assuming the code gets this far, we can start the ajax process
	_error.hide();

    sendAjax('POST', '../ajax/image.php', fd, 'json', true, _error, true, _display)
        // Reset the form.
    $(':input',_form)
        .not(':button, :submit, :reset, :hidden')
        .val('')
        .removeAttr('checked')
        .removeAttr('selected');

        $(':submit', _form)
            .html('Upload Another');

        $('.display').delay(1000).fadeOut(600);
})

/**
 * 
 * @param {string} requestType The header request type to be sent. Either POST or GET.
 * @param {string} requestUrl The file/url we are sending the ajax request to, to be handled.
 * @param {object} dataobject The dataobject to be sent.
 * @param {string} dType The dataType being sent being sent. Usually json.
 * @param {boolean} asyncBool Boolean to indicate if the request will be asynchrounous or not.
 * @param {string} _error The HTML Element that will display errors.
 * @param {boolean} formdata Bool to indicate if we are sending a form data object or not. Used to handle files.
 * @param {string} _display The HTML Element that will display success messages.
 */
function sendAjax(requestType, requestUrl, dataobject, dType, asyncBool, _error, formdata = false, _display = null) {
    // If the ajax request contains a form data object...
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
            
            // Displays message for uploaded files
            if (data.uploaded !== undefined) {
                _display
                    .html(data.uploaded)
                    .show();
            }

            // Displays any success messages.
            if (data.success !== undefined) {
                _error
                    .html(data.success)
                    .show();
            }

        })
        .fail(function ajaxFailed(e){
            // Ajax call failed
            console.log(e);
        })
    
        return false;

    } else {
        // Otherwise, there is no form data object. 
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
            // If a redirect was specified, handle it.
            } else if (data.redirect !== undefined) {
                window.location = data.redirect;
            }  
            
            // Displays any success messages.
            if (data.success !== undefined) {
                _error
                    .html(data.success)
                    .show();
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

/**
 * Shows a preview of an image on the image upload form
 * @param {object} input The input HTML element that is being changed
 */

function PreviewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#image-preview').attr('src', e.target.result);
            $('#image-preview').attr('width', '125px');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/**
 * Shows a preview of a song on the song upload form.
 * @param {object} inputFile The input HTML element that is being changed.
 * @param {object} previewElement The audio HTML player element where we want to display the song preview source.
 */
function PreviewAudio(inputFile, previewElement) {

    if (inputFile.files && inputFile.files[0] && $(previewElement).length > 0) {

        $(previewElement).stop();

        var reader = new FileReader();

        reader.onload = function (e) {

            $(previewElement).attr('src', e.target.result);
            var playResult = $(previewElement).get(0).play();

            if (playResult !== undefined) {
                playResult.then(_ => {
                    // Automatic playback started!
                    // Show playing UI.

                    $(previewElement).show();
                })
                    .catch(error => {
                        // Auto-play was prevented
                        // Show paused UI.

            $(previewElement).hide();
                        alert("File Is Not A Valid Media File");
                    });
            }
        };

        reader.readAsDataURL(inputFile.files[0]);
    }
    else {
        $(previewElement).attr('src', '');
        $(previewElement).hide();
        alert("File Not Selected");
    }
}

// Preview the profile pic on the user's edit profile page.
$("#profile-image").change(function () {
    PreviewImage(this);
});

// Preview the image on the image upload page.
$("#image-upload").change(function () {
    PreviewImage(this);
});

// Preview the song on the song upload page.
$("#song-upload").change(function () {
    PreviewAudio(this, $("#audio-preview"));
});



