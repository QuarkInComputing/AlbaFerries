function showSection(sectionId) {
    const sections = document.querySelectorAll('section');
    sections.forEach(section => section.style.display = 'none');
    document.getElementById(sectionId).style.display = 'block';

    const sectionLinks = document.querySelectorAll('.accountnav a');

    sectionLinks.forEach(link => {
        link.classList.remove('activelink');
    });

    var buttonId = sectionId+'link';
    var button = document.getElementById(buttonId);

    button.classList.add('activelink');
}