


// Fonction pour gérer l'affichage du menu déroulant et l'interaction
function initDropdown() {
    // Récupérer les éléments
    const dropdownBtn = document.getElementById('dropdownBtn');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const menuItems = dropdownMenu.querySelectorAll('li');
    const hiddenInput = document.getElementById('status');

    // Ajouter un événement clic pour chaque élément de menu
    menuItems.forEach(item => {
        item.addEventListener('click', () => {
            // Mettre à jour le texte du bouton avec l'option sélectionnée
            dropdownBtn.innerHTML = `${item.dataset.value} <span class="arrow"></span>`;
            // Mettre à jour la valeur du champ caché
            hiddenInput.value = item.dataset.value;
            // Fermer le menu après sélection (facultatif)
            dropdownMenu.style.display = 'none';
        });
    });

    // Optionnel : Fermer le menu lorsqu'on clique en dehors
    document.addEventListener('click', (e) => {
        if (!dropdownBtn.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Optionnel : Ré-ouvrir le menu au clic sur le bouton
    dropdownBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
}

// Appel de la fonction après le chargement du DOM
document.addEventListener('DOMContentLoaded', function() {
    initDropdown();
});


function togglePassword(id_appeller) {
    const passwordInput = document.getElementById(id_appeller);
    const parent = passwordInput.closest('.input-group'); // Récupère le conteneur parent
    const eyeIcon = parent.querySelector('#eye-toggle'); // Cherche l'icône dans ce conteneur
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.src = "Content/Images/eye_closed_icon.png"; // Change to eye closed
    } else {
        passwordInput.type = 'password';
        eyeIcon.src = "Content/Images/eye_icon.png"; // Change back to eye open
    }
}