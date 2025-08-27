function downloadPDF(id) {
    // Send request to generate PDF
    fetch(`generate.php?id=${id}`) // Fetch the PDF from the server using the provided ID
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok'); // Handle error if response is not OK
        }
        return response.blob(); // Convert the response to a blob (binary large object)
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob); // Create a URL for the blob
        const a = document.createElement('a'); // Create a temporary anchor element
        a.href = url; // Set the href attribute to the blob URL
        a.download = 'Card_ID.pdf'; // Set the download attribute with the desired file name
        document.body.appendChild(a); // Append the anchor to the document body
        a.click(); // Programmatically click the anchor to trigger the download
        a.remove(); // Remove the anchor element from the document
    })
    .catch(error => console.error('There was a problem with your fetch operation:', error)); // Log any errors
}
    
// Fetch resident information via AJAX
$.ajax({
    url: 'get_resident_info.php', // URL to send the request to
    type: 'GET', // Type of request (GET)
    data: { id: id }, // Data to send with the request (resident ID)
    dataType: 'json', // Expected data type of the response
    success: function(data) {
        if (data.error) {
            residentInfo.innerHTML = '<p>' + data.error + '</p>'; // Display error message if there's an error
        } else {
            // Populate the resident info with data from the response
            residentInfo.innerHTML = `
                <div class='profile-picture'><img src='${data.profile_picture}' alt='Profile Picture'></div>
                <h2>${data.full_name}</h2>
                <p><strong>IDNumber:</strong> ${data.id_number}</p>
                <p><strong>Sex:</strong> ${data.sex}</p>
                <p><strong>Birthplace:</strong> ${data.birthplace}</p>
                <p><strong>Birthdate:</strong> ${data.birthdate}</p>
                <p><strong>Age:</strong> ${data.age}</p>
                <p><strong>Civil Status:</strong> ${data.civil_status}</p>
                <p><strong>Contact:</strong> ${data.contact}</p>
                <p><strong>Height:</strong> ${data.height}</p>
                <p><strong>Weight:</strong> ${data.weight}</p>
                <p><strong>Citizenship:</strong> ${data.citizenship}</p>
                <p><strong>Religion:</strong> ${data.religion}</p>
                <p><strong>Occupation Status:</strong> ${data.occupation_status}</p>
                <p><strong>Occupation:</strong> ${data.occupation}</p>
                <p><strong>Additional Occupation:</strong> ${data.adding_occupation}</p>
                <p><strong>Address:</strong> ${data.address}</p>
                <div class='qr-code'><img src='${data.qr_code}' alt='QR Code'></div>
            `;
        }
        modal.style.display = 'block'; // Display the modal with the resident info
    },
    error: function() {
        residentInfo.innerHTML = '<p>Error fetching resident information.</p>'; // Display error message if AJAX request fails
        modal.style.display = 'block'; // Display the modal
    }
});

// Function to delete senior record
function deleteSenior(id) {
    if (confirm("Are you sure you want to delete this Person?")) { // Confirm before deleting
        var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest object
        xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) { // Check if request is complete and successful
                location.reload(); // Reload the page after successful deletion
            }
        };
        xhr.open("POST", "delete_senior.php", true); // Open a POST request to delete the senior
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded"); // Set the request header
        xhr.send("id=" + id); // Send the request with the senior ID
    }
}

// Function to filter table based on search input
function searchTable() {
    var input, filter, table, tr, td, i, j, txtValue;
    input = document.getElementById('searchBar'); // Get the search input element
    filter = input.value.toUpperCase(); // Convert the search input to uppercase
    table = document.getElementById('residentsTable'); // Get the residents table element
    tr = table.getElementsByTagName('tr'); // Get all table rows

    for (i = 1; i < tr.length; i++) { // Loop through table rows (skip the header row)
        tr[i].style.display = 'none'; // Initially hide the row
        td = tr[i].getElementsByTagName('td'); // Get all cells in the current row
        for (j = 0; j < td.length; j++) { // Loop through cells
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText; // Get the cell text
                if (txtValue.toUpperCase().indexOf(filter) > -1) { // Check if the cell text matches the filter
                    tr[i].style.display = ''; // Show the row if there's a match
                    break; // Exit the inner loop
                }
            }
        }
    }
}

$(document).ready(function() {
    $('#residentsTable').DataTable(); // Initialize DataTables on the residents table
});
