const links = document.querySelectorAll('.bookinglinks a');
const singleButton = document.getElementById('singlebutton');
const returnButton = document.getElementById('returnbutton');
const returnField = document.getElementById('returnfield');
const typeField = document.getElementById('ticketType');

function changeTicket(type) {
    links.forEach(link => {
        link.classList.remove('bookinglink');
        link.classList.remove('disabledlink');
    });

    if(type == 'single') {
        singleButton.classList.add('bookinglink');
        returnButton.classList.add('disabledlink');

        returnField.style.display = 'none';
    }

    if(type == 'return') {
        singleButton.classList.add('disabledlink');
        returnButton.classList.add('bookinglink');

        returnField.style.display = 'block';
    }

    typeField.value = type;
}