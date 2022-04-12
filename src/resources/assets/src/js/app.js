if (window.hasOwnProperty('feather'))
    window.feather.replace()

document.getElementById('hamburger')?.addEventListener('click', () => {
    document.getElementById('sidebar-container')?.classList?.add('active');
    document.getElementById('sidebar-backdrop')?.classList?.add('active');
});

document.getElementById('sidebar-backdrop')?.addEventListener('click', () => {
    document.getElementById('sidebar-container')?.classList?.remove('active');
    document.getElementById('sidebar-backdrop')?.classList?.remove('active');
})
