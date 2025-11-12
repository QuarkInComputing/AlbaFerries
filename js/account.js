// Detects changes

const accountFields = document.querySelectorAll('input');
const button = document.getElementById('applychanges');

for (var i=0; i<accountFields.length; i++) {
    accountFields[i].addEventListener("change", (event) => {
        button.disabled = false;
        button.classList.add("buttonenable");
    });
};