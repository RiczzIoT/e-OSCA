document.addEventListener("DOMContentLoaded", function() {
    const adminPasswordHash = '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918';
  
    // Function to disable all buttons, links, and input fields
    function disableInterface() {
        const buttons = document.querySelectorAll('button');
        const links = document.querySelectorAll('a');
        const inputs = document.querySelectorAll('input');
  
        buttons.forEach(button => {
            button.disabled = true;
        });
  
        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
            });
        });
  
        inputs.forEach(input => {
            input.disabled = true;
        });
  
        alert('Free demo is expired please contact the developer RiczzTV on Facebook Thanks! :)');
        localStorage.setItem('interfaceDisabled', 'true');
    }
  
    function enableInterface() {
        const buttons = document.querySelectorAll('button');
        const links = document.querySelectorAll('a');
        const inputs = document.querySelectorAll('input');
  
        buttons.forEach(button => {
            button.disabled = false;
        });
  
        links.forEach(link => {
            link.replaceWith(link.cloneNode(true)); // Remove event listeners
        });
  
        inputs.forEach(input => {
            input.disabled = false;
        });
  
        localStorage.removeItem('interfaceDisabled');
    }
  
    if (localStorage.getItem('interfaceDisabled') === 'true') {
        disableInterface();
    } else {
        setTimeout(disableInterface, 3000);
    }
  
    document.addEventListener('keydown', function(event) {
        if (event.ctrlKey && event.altKey && event.key === 'd') {
            const password = prompt('Enter admin password:');
            const hashedPassword = CryptoJS.SHA256(password).toString();
  
            if (hashedPassword === adminPasswordHash) {
                enableInterface();
                alert('Interface enabled.');
            } else {
                alert('Incorrect password.');
            }
        }
    });
  });