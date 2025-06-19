function makeTableSortable(tableId) {
    const table = document.getElementById(tableId);
    const headers = table.querySelectorAll("th");
    let sortDirection = 1; // 1 = ASC, -1 = DESC

    headers.forEach((header, columnIndex) => {
        header.style.cursor = "pointer";
        header.addEventListener("click", () => {
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.querySelectorAll("tr"));

            // Toggle direction if the same column is clicked again
            sortDirection = header.dataset.sorted === "asc" ? -1 : 1;
            headers.forEach(h => h.removeAttribute("data-sorted")); // reset all
            header.dataset.sorted = sortDirection === 1 ? "asc" : "desc";

            rows.sort((a, b) => {
                const aText = a.children[columnIndex].textContent.trim();
                const bText = b.children[columnIndex].textContent.trim();

                const aVal = isNaN(aText) ? aText.toLowerCase() : parseFloat(aText);
                const bVal = isNaN(bText) ? bText.toLowerCase() : parseFloat(bText);

                if (aVal < bVal) return -1 * sortDirection;
                if (aVal > bVal) return 1 * sortDirection;
                return 0;
            });

            // Re-add sorted rows to tbody
            tbody.innerHTML = "";
            rows.forEach(row => tbody.appendChild(row));
        });
    });
}

// Activer le tri sur les deux tableaux
makeTableSortable("capteursTable");
makeTableSortable("actionneursTable");
function setupFilter(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId).getElementsByTagName("tbody")[0];

    input.addEventListener("input", () => {
        const filter = input.value.toLowerCase();
        const rows = table.getElementsByTagName("tr");

        Array.from(rows).forEach(row => {
            const rowText = row.textContent.toLowerCase();
            row.style.display = rowText.includes(filter) ? "" : "none";
        });
    });
}

makeTableSortable("capteursTable");
makeTableSortable("actionneursTable");

setupFilter("searchCapteurs", "capteursTable");
setupFilter("searchActionneurs", "actionneursTable");

document.querySelectorAll('.modifierLimites').forEach(button => {
    button.addEventListener('click', () => {
        const id = button.getAttribute('data-id');
        const min = button.getAttribute('data-min');
        const max = button.getAttribute('data-max');
        const nom = button.getAttribute('data-nom');

        document.getElementById('modal-id-sensor').value = id;
        document.getElementById('sensor-nom').textContent = nom;
        document.getElementById('lim_min').value = min;
        document.getElementById('lim_max').value = max;

        const modal = new bootstrap.Modal(document.getElementById('limiteModal'));
        modal.show();
    });
});




