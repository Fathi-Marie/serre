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
            if (i === currentPage) btn.classList.add('btn-primary');
            else btn.classList.add('btn-outline-primary');

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

