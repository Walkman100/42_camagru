function overlays()
{
    var overlay_div = document.getElementById('wc-overlays');

    overlay_div.innerHTML = '';

    var overlay_arr = [];
    var overlay_form = document.forms['formupload'];
    for (var element in overlay_form)
    {
        var formElement = overlay_form[element];
        if (overlay_form.hasOwnProperty(element) && formElement.name && formElement.type == 'checkbox' && formElement.checked == true)
            overlay_arr.push(formElement.value);
    }

    overlayPosX = 10;
    overlay_arr.forEach(id => {
            var img = document.createElement("img");
            img.className = 'wc-overlay';
            img.src = 'overlays/' + id + '.png';
            overlay_div.appendChild(img);
        });
}
