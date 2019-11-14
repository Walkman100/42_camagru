const video         = document.getElementById('videoElement');
const startButton   = document.getElementById('btnstart');
const stopButton    = document.getElementById('btnstop');
const captureButton = document.getElementById('btncapture');
const uploadButton  = document.getElementById('btnupload');
const img           = document.getElementById('captureImage');
const canvas        = document.getElementById('captureCanvas');
const webcamdiv     = document.getElementById('webcamdiv');

if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia)
{
    webcamdiv.style.display = 'none';
    alert('getUserMedia() is not supported by your browser!');
}

startButton.onclick = function()
{
    navigator.mediaDevices.getUserMedia({
            video: {
                width: 1280, height: 720
            }
        }).then(function(stream) {
            video.srcObject = stream;
            stopButton.disabled = false;
            captureButton.disabled = false;
        }).catch(function (error) {
            alert('Request to get webcam stream was denied!');
        });
}

stopButton.onclick = function()
{
    video.srcObject.getTracks().forEach(function(track) {
            track.stop();
            stopButton.disabled = true;
        });
}

captureButton.onclick = video.onclick = function()
{
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    img.src = canvas.toDataURL('image/png');
};

uploadButton.onclick = function()
{
    uploadButton.disabled = true;
    canvas.toBlob(function(blob) {
        // create new form, append image
        var formData = new FormData();
        formData.append('MAX_FILE_SIZE', '35000000');
        formData.append('userfile', blob, 'webcamimage.png');
        var action = 'api/upload';

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
                uploadButton.disabled = false;
                document.getElementById('wc-upload-status').innerHTML = '';
            }
        };

        // add events
        xhr.upload.addEventListener('loadstart', function(evt) {
                    document.getElementById('wc-upload-status').innerHTML = 'Upload started.'
                }, false);
        xhr.upload.addEventListener('progress', function(evt) {
                    var percent = Math.floor(evt.loaded / evt.total * 100);
                    document.getElementById('wc-upload-progress').innerHTML = 'Progress: ' + percent + '%';
                }, false);
        xhr.upload.addEventListener('load', function(evt) {
                    document.getElementById('wc-upload-progress').innerHTML = '';
                    document.getElementById('wc-upload-status').innerHTML = 'File uploaded. Waiting for response.';
                }, false);

        // send the request
        xhr.send(formData);
    }, 'image/png');
    return (false);
}
