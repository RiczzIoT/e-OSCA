function calculateAge() {
    const birthdate = document.getElementById('birthdate').value;
    const ageInput = document.getElementById('age');

    if (birthdate) {
        const today = new Date();
        const birthDate = new Date(birthdate);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();

        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        ageInput.value = age;
    } else {
        ageInput.value = '';
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    document.getElementById('birthdate').addEventListener('change', calculateAge);
});