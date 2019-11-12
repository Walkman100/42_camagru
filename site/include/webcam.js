const video = document.getElementById("videoElement");
const startButton = document.getElementById('btnstart');
const stopButton = document.getElementById('btnstop');
const captureButton = document.getElementById('btncapture');
const img = document.getElementById('captureImage');
const canvas = document.getElementById('captureCanvas');
const webcamdiv = document.getElementById('webcamdiv');

if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia)
{
    webcamdiv.style.display = "none";
    alert('getUserMedia() is not supported by your browser!');
}

startButton.onclick = function() {
    navigator.mediaDevices.getUserMedia({
        video: {
            width: 1280, height: 720
        }
    }).then(function(stream) {
        video.srcObject = stream;
        stopButton.disabled = false;
        captureButton.disabled = false;
    }).catch(function (error) {
        webcamdiv.style.display = "none";
        console.log("User denied the request");
    });
}

stopButton.onclick = function() {
    video.srcObject.getTracks().forEach(function(track) {
        track.stop();
        stopButton.disabled = true;
    });
}

captureButton.onclick = video.onclick = function() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);
    img.src = canvas.toDataURL('image/webp');
};
