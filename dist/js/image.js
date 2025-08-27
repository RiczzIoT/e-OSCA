document.addEventListener('DOMContentLoaded', function() {
    const popupImageContainer = document.getElementById('popup-image');
    const popupImage = document.getElementById('popup-image-img');
    
    document.querySelectorAll('td[data-image]').forEach(function(emailCell) {
        emailCell.addEventListener('mouseover', function(event) {
            const imageUrl = emailCell.getAttribute('data-image');
            if (imageUrl) {
                popupImage.src = imageUrl;
                popupImageContainer.style.display = 'block';
                popupImageContainer.style.top = (event.clientY + window.scrollY + -170) + 'px';
                popupImageContainer.style.left = (event.clientX + window.scrollX + -250) + 'px';
            }
        });

        emailCell.addEventListener('mousemove', function(event) {
            popupImageContainer.style.top = (event.clientY + window.scrollY + -170) + 'px';
            popupImageContainer.style.left = (event.clientX + window.scrollX + -250) + 'px';
        });

        emailCell.addEventListener('mouseout', function() {
            popupImageContainer.style.display = 'none';
        });
    });
});