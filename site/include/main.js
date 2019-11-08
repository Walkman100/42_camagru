function submitForm(formName) {
    var form = document.forms[formName];

    var action = form.attributes.action.value;

    var request_str = "";

    for (var element in form)
    {
        if (form.hasOwnProperty(element) && form[element].name && element != "action")
        {
            if (request_str != "")
                request_str += "&";
            request_str += encodeURI(form[element].name) + "=" + encodeURI(form[element].value);
        }
    }
    console.log("request: " + action + "?" + request_str);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', action);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            alert(xhr.responseText);
        }
        else {
            alert(xhr.responseText);
        }
    };

    xhr.send(request_str);
    return (false);
}
