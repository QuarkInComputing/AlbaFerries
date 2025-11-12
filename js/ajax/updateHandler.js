const updateForm = document.getElementById('updateform');
updateForm.addEventListener('submit', e => {
    e.preventDefault();

    updateData(updateForm);
});

function updateData(form){
    var formData = new FormData(form);

    fetch('/php/user/updateManager.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(text => {
        if (text.startsWith('updated')) {
            window.location.reload(true);
        } else {
            alert('Error: '+text)
        }
    })
        .catch(error => {
        console.error('AJAX error:', error);
    });
}