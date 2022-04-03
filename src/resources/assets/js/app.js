if (window.hasOwnProperty('feather'))
    window.feather.replace();

document.querySelectorAll('[data-link]').forEach(elem => {
    elem.addEventListener('click', event => {
        event.preventDefault();
        location.href = elem.dataset.link;
    });
});

document.querySelectorAll('[data-link] a').forEach(elem => {
    elem.addEventListener('click', event => {
        event.stopPropagation();
    });
});
