 // Function to set today's date as default for residency start date
 function setTodayDate() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('residency_start_date').value = today;
}

// Function to search residents and auto-fill form
function searchResident() {
    const query = document.getElementById('search').value;
    if (query.length >= 3) { // Minimum 3 characters to search
        fetch('../certification/search_resident.php?query=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                if (data && data.id) {
                    document.getElementById('id_number').value = data.id_number;
                    document.getElementById('first_name').value = data.first_name;
                    document.getElementById('middle_name').value = data.middle_name || '';
                    document.getElementById('last_name').value = data.last_name;
                    document.getElementById('suffix').value = data.suffix || '';
                    document.getElementById('sex').value = data.sex;
                    document.getElementById('birthplace').value = data.birthplace;
                    document.getElementById('birthdate').value = data.birthdate;
                    document.getElementById('age').value = data.age;
                    document.getElementById('requestor').value = data.requestor || '';
                    document.getElementById('civil_status').value = data.civil_status;
                    document.getElementById('contact_type').value = data.contact_type || '';
                    document.getElementById('contact').value = data.contact;
                    document.getElementById('height').value = data.height || '';
                    document.getElementById('weight').value = data.weight || '';
                    document.getElementById('citizenship').value = data.citizenship;
                    document.getElementById('religion').value = data.religion;
                    document.getElementById('occupation_status').value = data.occupation_status || '';
                    document.getElementById('occupation').value = data.occupation || '';
                    document.getElementById('adding_occupation').value = data.adding_occupation || '';
                    document.getElementById('unit_room_floor').value = data.unit_room_floor || '';
                    document.getElementById('building_name').value = data.building_name || '';
                    document.getElementById('lot').value = data.lot || '';
                    document.getElementById('block').value = data.block || '';
                    document.getElementById('phase').value = data.phase || '';
                    document.getElementById('house_number').value = data.house_number;
                    document.getElementById('street').value = data.street || '';
                    document.getElementById('subdivision').value = data.subdivision || '';
                    document.getElementById('zone_no').value = data.zone_no;
                    document.getElementById('address_type').value = data.address_type;
                } else {
                    clearForm();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                clearForm();
            });
    } else {
        clearForm();
    }
}

// Function to clear the form
function clearForm() {
    document.getElementById('id_number').value = '';
    document.getElementById('first_name').value = '';
    document.getElementById('middle_name').value = '';
    document.getElementById('last_name').value = '';
    document.getElementById('suffix').value = '';
    document.getElementById('sex').value = '';
    document.getElementById('birthplace').value = '';
    document.getElementById('birthdate').value = '';
    document.getElementById('age').value = '';
    document.getElementById('requestor').value = '';
    document.getElementById('barangay').value = '';
    document.getElementById('civil_status').value = '';
    document.getElementById('contact_type').value = '';
    document.getElementById('mothers_name').value = '';
    document.getElementById('fathers_name').value = '';
    document.getElementById('contact').value = '';
    document.getElementById('height').value = '';
    document.getElementById('weight').value = '';
    document.getElementById('citizenship').value = '';
    document.getElementById('religion').value = '';
    document.getElementById('occupation_status').value = '';
    document.getElementById('occupation').value = '';
    document.getElementById('adding_occupation').value = '';
    document.getElementById('unit_room_floor').value = '';
    document.getElementById('building_name').value = '';
    document.getElementById('lot').value = '';
    document.getElementById('block').value = '';
    document.getElementById('phase').value = '';
    document.getElementById('house_number').value = '';
    document.getElementById('street').value = '';
    document.getElementById('subdivision').value = '';
    document.getElementById('zone_no').value = '';
    document.getElementById('address_type').value = '';
    document.getElementById('certificate_count').value = '';
}

// Set today's date on page load
document.addEventListener('DOMContentLoaded', setTodayDate);
document.addEventListener('DOMContentLoaded', setTodayDate);