// pauseDebugger.js

(function() {
    "use strict";
  
    // Function to pause the debugger
    function pauseDebugger() {
      // Add a debugger statement to pause the code execution
      debugger;
    }
  
    // Function to continuously check and pause the debugger
    function continuouslyPauseDebugger() {
      setInterval(pauseDebugger, 1000); // Adjust the interval as needed
    }
  
    // Start continuously pausing the debugger
    continuouslyPauseDebugger();
  
    // Add an event listener to catch when the DevTools is opened
    window.addEventListener('devtoolschange', event => {
      if (event.detail.isOpen) {
        continuouslyPauseDebugger();
      }
    });
  
    // Function to detect if DevTools is open
    function detectDevTools() {
      const element = new Image();
      Object.defineProperty(element, 'id', {
        get: function() {
          continuouslyPauseDebugger();
        }
      });
      console.log(element);
    }
  
    detectDevTools();
  })();
  