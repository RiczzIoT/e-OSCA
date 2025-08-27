let inactivityTime = 100000; // Time in milliseconds (e.g., 5000ms = 5 seconds)
let inactivityTimer;

// Function to toggle blur and save the state in localStorage
function toggleBlur() {
    const blurOverlay = document.getElementById('blur-overlay');
    if (blurOverlay.classList.contains('hidden')) {
        blurOverlay.classList.remove('hidden');
        localStorage.setItem('isBlurred', 'true');
    } else {
        blurOverlay.classList.add('hidden');
        localStorage.setItem('isBlurred', 'false');
    }
}

// Function to load the blur state from localStorage
function loadBlurState() {
    const isBlurred = localStorage.getItem('isBlurred');
    const blurOverlay = document.getElementById('blur-overlay');
    if (isBlurred === 'true') {
        blurOverlay.classList.remove('hidden');
    } else {
        blurOverlay.classList.add('hidden');
    }
}

// Function to start inactivity timer
function startInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
        const blurOverlay = document.getElementById('blur-overlay');
        blurOverlay.classList.remove('hidden');
        localStorage.setItem('isBlurred', 'true');
    }, inactivityTime);
}

// Load the blur state when the page loads
document.addEventListener('DOMContentLoaded', function() {
    loadBlurState();
    startInactivityTimer();
});

// Reset the inactivity timer on mouse movement
document.addEventListener('mousemove', function() {
    clearTimeout(inactivityTimer);
    if (!document.getElementById('blur-overlay').classList.contains('hidden')) {
        startInactivityTimer();
    }
});

// Add event listener for hotkey Ctrl + Alt + 1
document.addEventListener('keydown', function(event) {
    if (event.ctrlKey && event.altKey && event.key === '1') {
        toggleBlur();
        if (document.getElementById('blur-overlay').classList.contains('hidden')) {
            startInactivityTimer();
        }
    }
});
