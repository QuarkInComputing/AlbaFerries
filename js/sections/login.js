function showSection(sectionId) {
    const sections = document.querySelectorAll('section');
    sections.forEach(section => section.style.display = 'none');
    document.getElementById(sectionId).style.display = 'block';

    if(sectionId == 'register') {
        document.body.style.backgroundImage = "url('../media/large/pexels-liam-o-neill-the-explorer-panda-114076-1045075.webp')";
    } else {
        document.body.style.backgroundImage = "url('../media/large/pexels-miguel-martinez-1241576-2433283.webp')";
    }
}