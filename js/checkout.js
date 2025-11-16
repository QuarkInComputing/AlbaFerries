const checkoutFields = document.querySelectorAll('input');
const button = document.getElementById('purchasebutton');

const wheelchairFields = document.querySelectorAll('#wheelchair');

for (var i=0; i<checkoutFields.length; i++) {
    checkoutFields[i].addEventListener("change", (event) => {
        if(checkoutCheck(checkoutFields)){
            button.disabled = false;
            button.classList.add("buttonenable");
        } else {
            button.disabled = true;
            button.classList.remove("buttonenable");
        }
    });
};

wheelchairFields.forEach(check => {
    check.addEventListener('change', () => {
        if (check.checked) {
            wheelchairFields.forEach(other => {
                if (other !== check) other.disabled = true;
            });
        } else {
            wheelchairFields.forEach(other => other.disabled = false);
        }
    });
});

function checkoutCheck(checkoutFields) {
    for (var i=0; i<checkoutFields.length; i++) {
        if(!checkoutFields[i].value) {
            return false;
        }
    }
    return true;
}