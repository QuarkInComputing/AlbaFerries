const from = document.getElementById('from');
const to = document.getElementById('to');

from.addEventListener('change', updateRestrictions);
to.addEventListener('change', updateRestrictions);

function updateRestrictions() {
    const fromValue = from.value;
    const toValue = to.value;

    Array.from(to.options).forEach(opt => opt.disabled = false);
    Array.from(from.options).forEach(opt => opt.disabled = false);

    //Muck to rum
    if (fromValue === 'rum') {
        to.querySelector('option[value="muck"]').disabled = true;
    } else if (fromValue === 'muck') {
        to.querySelector('option[value="rum"]').disabled = true;
    }

    if (toValue === 'rum') {
        from.querySelector('option[value="muck"]').disabled = true;
    } else if (toValue === 'muck') {
        from.querySelector('option[value="rum"]').disabled = true;
    }

    if (to.value === fromValue || 
        (fromValue === 'rum' && to.value === 'muck') || 
        (fromValue === 'muck' && to.value === 'rum')) {
        to.value = '';
    }
    if (from.value === toValue || 
        (toValue === 'rum' && from.value === 'muck') || 
        (toValue === 'muck' && from.value === 'rum')) {
        from.value = '';
    }

    //Identical from to
    if (fromValue) to.querySelector(`option[value="${fromValue}"]`).disabled = true;
    if (toValue) from.querySelector(`option[value="${toValue}"]`).disabled = true;
}