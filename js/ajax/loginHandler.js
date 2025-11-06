document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();
        const formType = form.querySelector('[name="formType"]').value;

        if(formType == 'login') {
            console.log('Login button pressed');
        } else {
            console.log('Register button pressed');
        }

        sendData(formType);
    });
});

function sendData(formType){
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById(formType+'form')

        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(form);

            if(!validateClient(formType)) {
                return;
            }

            fetch('/php/user/loginManager.php', {
                method: 'POST',
                body: formData
            })

            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Success!')
                } else {
                    console.log('Error!')
                }
            })

            .catch(error => {
                console.error('AJAX error:', error);
            });
        });
    });
};

function validateClient (formType) {
    const emailFields = document.querySelectorAll('#email');
    const passwordFields = document.querySelectorAll('#password');

    if(formType == 'login') {
        var email = emailFields[0];
        var password = passwordFields[0];
    } else {
        var email = emailFields[1];
        var password = passwordFields[1];
        var forename = document.getElementById('forename').value.trim();
        var surname = document.getElementById('surname').value.trim();
        var dob = document.getElementById('dob').value.trim();
        var phone = document.getElementById('phone').value.trim();
    }

    var isValid = true;

    if(email === "" && !/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        document.getElementById("emailError").textContent = "Please enter a valid email!";
        isValid = false;
    }

    if(password === "") {
        document.getElementById("emailError").textContent = "Password cannot be blank!";
        isValid = false;
    }

    if(formType == 'register' && forename == "") {
        document.getElementById("forenameError").textContent = "Forename cannot be blank!";
        isValid = false;
    }

    if (formType == 'register' && surname == "") {
        document.getElementById("surnameError").textCcontent = "Surname cannot be blank!";
        isValid = false;
    }

    // if (formType == 'register' && dob == "") {
    //     document.getElementById("surnameError").textCcontent = "Please select a valid date of birth!";
    //     isValid = false;
    // } 
    // Checking for a valid date of birth is another headache ill worry about later

    if (formType == 'register' && phone == "" || phone.length < 11) {
        document.getElementById("surnameError").textCcontent = "Please enter a valid phone number!";
        isValid = false;
    }

    return isValid;
}

