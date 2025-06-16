const sidebar = document.getElementById('sidebarMenu');
const toggleBtn = document.getElementById('sidebarToggle');

toggleBtn.addEventListener('click', () => {
    sidebar.classList.toggle('show');
});