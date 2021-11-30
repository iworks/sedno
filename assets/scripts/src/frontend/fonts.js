;
window.addEventListener('load', function() {
    const head = document.getElementsByTagName('head')[0];
    const link = document.createElement('link');
    link.href = '//fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap&subset=latin-ext';
    link.rel = 'stylesheet';
    head.append(link);
    document.body.classList.add('fonts-added');
}, {passive: true} );
