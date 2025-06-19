document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', () => {
        console.log('Recherche :', searchInput.value);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const rowsPerPage = 20;
    const table = document.getElementById('usersTable');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const searchInput = document.getElementById('searchInput');
    const pagination = document.getElementById('pagination');

    let filteredRows = rows;
    let currentPage = 1;

    function displayRows() {
        tbody.innerHTML = '';
        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageRows = filteredRows.slice(start, end);
        pageRows.forEach(row => tbody.appendChild(row.cloneNode(true)));
    }

    function setupPagination() {
        pagination.innerHTML = '';
        const pageCount = Math.ceil(filteredRows.length / rowsPerPage);

        for (let i = 1; i <= pageCount; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.classList.add('btn', 'btn-sm', 'mx-1');
            if (i === currentPage) btn.classList.add('numero_page');
            else btn.classList.add('numero_page_suivant');

            btn.addEventListener('click', () => {
                currentPage = i;
                displayRows();
                setupPagination();
            });

            pagination.appendChild(btn);
        }
    }

    function filterRows() {
        const filter = searchInput.value.toLowerCase();
        filteredRows = rows.filter(row => {
            return [...row.cells].some(cell =>
                cell.textContent.toLowerCase().includes(filter)
            );
        });
        currentPage = 1;
        displayRows();
        setupPagination();
    }

    searchInput.addEventListener('input', filterRows);

    // Initial display
    displayRows();
    setupPagination();
});

function toggleRole(userId, currentRole) {
    const newRole = currentRole === 'Admin' ? 'Visiteur' : 'Admin';
    const confirmMessage = `Voulez-vous vraiment changer le rôle en "${newRole}" ?`;

    if (confirm(confirmMessage)) {
        fetch("?controller=admin&action=toggle_role", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: userId, role: newRole })
        })
            .then(response => {
                console.log("Status:", response.status);
                return response.text(); // Pas .json() pour mieux diagnostiquer
            })
            .then(text => {
                console.log("Réponse brute du serveur:", text); // ← regarde ça dans la console
                try {
                    const data = JSON.parse(text);
                    if (!data) {
                        throw new Error("Aucune donnée");
                    }
                    alert(data.message);
                    if (data.success) {
                        window.location.reload();
                    }
                } catch (e) {
                    alert("Erreur : réponse JSON invalide.");
                    console.error("Erreur JSON:", e);
                    console.warn("Réponse brute:", text);
                }
            })
            .catch(error => {
                alert("Rôle mis a jour.");
                console.error(error);
            });
    }
}

