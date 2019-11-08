function changeDisabled(form, disabled)
{
    var elements = form.elements;
    for (var i = 0, len = elements.length; i < len; ++i)
    {
        elements[i].disabled = disabled;
    }
}

function submitForm(formName) {
    // get the requested form
    var form = document.forms[formName];

    // disable the form
    changeDisabled(form, true);

    // get the form action
    var action = form.attributes.action.value;

    // build the request string
    var request_str = "";
    for (var element in form)
    {                        // ignore elements without a name, and identifiers that are "action"
        if (form.hasOwnProperty(element) && form[element].name && element != "action")
        {
            if (request_str != "") // add an & between fields
                request_str += "&";
            request_str += encodeURI(form[element].name) + "=" + encodeURI(form[element].value);
        }
    }
    console.log("request: " + action + "?" + request_str);

    // build the request object and actions
    var xhr = new XMLHttpRequest();
    xhr.open('POST', action);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert(xhr.responseText);
            location.reload(true);
        }
        else {
            alert(xhr.responseText);
            changeDisabled(form, false);
        }
    };

    // send the request
    xhr.send(request_str);
    return (false);
}
