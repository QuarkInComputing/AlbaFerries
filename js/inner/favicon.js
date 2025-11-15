const favicon = document.getElementById('favicon');

let fav1 = '<link rel="apple-touch-icon" sizes="180x180" href="/media/favicon/apple-touch-icon.png">';
let fav2 = '<link rel="icon" type="image/png" sizes="32x32" href="/media/favicon/favicon-32x32.png">';
let fav3 = '<link rel="icon" type="image/png" sizes="16x16" href="/media/favicon/favicon-16x16.png">';
let fav4 = '<link rel="manifest" href="/media/favicon/site.webmanifest"></link>';

favicon.innerHTML = fav1+fav2+fav3+fav4;