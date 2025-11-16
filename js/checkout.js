var checkoutFields = document.querySelectorAll('input');
const button = document.getElementById('purchasebutton');

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

function checkoutCheck(checkoutFields) {
    for (var i=0; i<checkoutFields.length; i++) {
        if(!checkoutFields[i].value) {
            return false;
        }
    }
    return true;
}