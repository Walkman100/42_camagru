/**
 * Set a form's disabled state
 * @param {HTMLFormElement} form        Form to change
 * @param {boolean}         disabled    True to disable the form, false to enable
 */
function changeDisabled(form, disabled)
{
    var elements = form.elements;
    for (var i = 0, len = elements.length; i < len; ++i)
    {
        elements[i].disabled = disabled;
    }
}

/**
 * Perform an AJAX (XHR) request
 * @param {string}          action      Where to submit the string to
 * @param {HTMLFormElement} form        Form to re-enable on error
 * @param {string}          request_str Request to POST to action
 */
function XHR(action, form, request_str)
{
    // build the request object and actions
    var xhr = new XMLHttpRequest();
    xhr.open('POST', action);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200)
        {
            alert(xhr.responseText);
            location.reload(true);
        }
        else
        {
            alert(xhr.responseText);
            changeDisabled(form, false);
        }
    };

    // send the request
    xhr.send(request_str);
}

/**
 * Submits a form using AJAX (XHR). All elements of the form are used, so which button a user clicked is ignored
 * @param   {string}        formName    Name of the form's ID to submit
 * @returns {boolean}                   False so onsubmit is cancelled
 */
function submitForm(formName)
{
    // get the requested form
    var form = document.forms[formName];
    // disable the form
    changeDisabled(form, true);
    // get the form action
    var action = form.attributes.action.value;

    // build the request string
    var request_str = "";
    for (var element in form)
    {                // ignore elements without a name, identifiers that are "action", radio buttons that aren't checked
        if (form.hasOwnProperty(element) && form[element].name && element != "action" &&
                        !(form[element].type == "radio" && form[element].checked == false))
        {
            if (request_str != "") // add an & between fields
                request_str += "&";
            request_str += encodeURI(form[element].name) + "=" + encodeURI(form[element].value);
        }
    }
    console.log("request: " + action + "?" + request_str);

    XHR(action, form, request_str);
    return (false);
}

/**
 * Submits a multibutton form using AJAX (XHR). If no button is clicked (e.g. User pressed enter), an error message is shown
 * @param   {string}        formName    Name of the form's ID to submit
 * @returns {boolean}                   False so onsubmit is cancelled
 */
function submitMultibuttonForm(formName)
{
    // get focused element
    // Firefox || Opera || IE || unsupported
    var target = event.explicitOriginalTarget || event.relatedTarget || document.activeElement || {};

    // get the value of the focused element
    var submit_action;
    if (target.type == "submit")
        submit_action = target.value;
    else
    {
        alert("No button clicked! Please click a button...");
        return (false);
    }

    // get the requested form
    var form = document.forms[formName];
    // disable the form
    changeDisabled(form, true);
    // get the form action
    var action = form.attributes.action.value;

    // build the request string
    var request_str = "";
    for (var element in form)
    {        // ignore elements without a name, identifiers that are "action", radio buttons that aren't checked, and names that are action
        if (form.hasOwnProperty(element) && form[element].name && element != "action" &&
                        !(form[element].type == "radio" && form[element].checked == false) && form[element].name != 'action')
        {
            if (request_str != "") // add an & between fields
                request_str += "&";
            request_str += encodeURI(form[element].name) + "=" + encodeURI(form[element].value);
        }
    }

    if (request_str != "")
        request_str += "&";
    request_str += encodeURI('action') + '=' + encodeURI(submit_action);

    console.log("request: " + action + "?" + request_str);

    XHR(action, form, request_str);
    return (false);
}

/**
 * Submits a form with an image upload.
 * @param   {string}        formName    Name of the form's ID to submit
 * @returns {boolean}                   False so onsubmit is cancelled
 */
function submitUploadForm(formName)
{
    // get the requested form
    var form = document.forms[formName];
    // create a formData object, used for uploading a file
    var formData = new FormData(form);
    // disable the form
    changeDisabled(form, true);
    // get the form action
    var action = form.attributes.action.value;

    // build the request object and actions
    var xhr = new XMLHttpRequest();
    xhr.open('POST', action);
    xhr.onload = function()
    {
        if (xhr.status === 200)
        {
            alert(xhr.responseText);
            location.reload(true);
        }
        else
        {
            alert(xhr.responseText);
            document.getElementById('upload-status').innerHTML = '';
            changeDisabled(form, false);
        }
    };

    // add events
    xhr.upload.addEventListener('loadstart', function(evt) {
                document.getElementById('upload-status').innerHTML = 'Upload started.'
            }, false);
    xhr.upload.addEventListener('progress', function(evt) {
                var percent = Math.floor(evt.loaded / evt.total * 100);
                document.getElementById('upload-progress').innerHTML = 'Progress: ' + percent + '%';
            }, false);
    xhr.upload.addEventListener('load', function(evt) {
                document.getElementById('upload-progress').innerHTML = '';
                document.getElementById('upload-status').innerHTML = 'File uploaded. Waiting for response.';
            }, false);

    // send the request
    xhr.send(formData);
    return (false);
}
