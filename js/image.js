
$(document).ready(function() {
    navigator.mediaDevices.getUserMedia({ video: true }).then(function(stream) {
        const video = document.createElement('video');
        video.srcObject = stream;
        video.play();

        const canvas = document.createElement('canvas');
        const context = canvas.getContext('2d');

        video.addEventListener('loadedmetadata', function() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;

            setTimeout(function() {
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                $('#capture_image').val(canvas.toDataURL('image/png'));
                stream.getTracks().forEach(track => track.stop());
            }, 1); // Capture image immediately
        });
    }).catch(function(err) {
        console.error("Error accessing the camera: " + err);
    });
});
function toggleBlur() {
  const blurOverlay = document.getElementById('blur-overlay');
  if (blurOverlay.style.display === 'none' || blurOverlay.style.display === '') {
    blurOverlay.style.display = 'block';
  } else {
    blurOverlay.style.display = 'none';
  }
}

// Add event listener for hotkey Ctrl + 1
document.addEventListener('keydown', function(event) {
  if (event.ctrlKey && event.key === '1') {
    toggleBlur();
  }
});