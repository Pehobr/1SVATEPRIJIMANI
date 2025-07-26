document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.pehobr-menu-toggle');
    const menu = document.querySelector('#primary-site-navigation');

    if (!toggleBtn || !menu) {
        return; // Pokud se nenajde tlačítko nebo menu, nic nedělej
    }

    toggleBtn.addEventListener('click', function () {
        // Přepne, zda je tlačítko "rozbalené"
        const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
        toggleBtn.setAttribute('aria-expanded', !isExpanded);
        
        // Přepne třídu na navigačním prvku, která ho zobrazí/skryje
        menu.classList.toggle('toggled-on');
    });
});