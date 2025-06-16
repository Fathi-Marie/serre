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

document.addEventListener("DOMContentLoaded", function () {
    const chartCanvas = document.getElementById("capteurChart");
    let myChart = null;

    document.querySelectorAll(".voirGraph").forEach(btn => {
        btn.addEventListener("click", async function (e) {
            e.preventDefault();

            const capteurId = this.dataset.id;
            const capteurName = this.dataset.name;

            try {
                const response = await fetch(`?controller=admindash&action=get_graph_data&id_sensor=${capteurId}`);

                if (!response.ok) {
                    throw new Error(`Erreur HTTP ${response.status} - ${response.statusText}`);
                }

                const text = await response.text();
                console.log("Réponse brute du serveur :", text);

                let data;
                try {
                    data = JSON.parse(text);
                } catch (jsonError) {
                    throw new Error("Erreur lors du parsing JSON :\n" + jsonError.message + "\nRéponse brute :\n" + text);
                }

                if (!data || !data.labels || !data.values) {
                    throw new Error("Structure des données invalide reçue du serveur.");
                }

                // Formatage des dates
                const formattedLabels = data.labels.map(dateStr => {
                    const date = new Date(dateStr);
                    const day = String(date.getDate()).padStart(2, '0');
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const year = date.getFullYear();
                    return `${day}/${month}/${year}`;
                });

                // Detruit le chart précédent s’il existe
                if (myChart) myChart.destroy();

                // Prépare l’annotation si limMax valide
                const limMax = parseFloat(data.lim_max);
                const annotations = {};
                if (!isNaN(limMax)) {
                    annotations.limiteMax = {
                        type: 'line',
                        yMin: limMax,
                        yMax: limMax,
                        xMin: 0,
                        xMax: data.labels.length - 1,
                        borderColor: 'red',
                        borderWidth: 2,
                        label: {
                            content: 'Limite max',
                            enabled: true,
                            position: 'start',
                            backgroundColor: 'red',
                            color: 'white'
                        }
                    };
                    console.log("Limite max:", limMax);
                } else {
                    console.warn("Limite max invalide ou absente, aucune annotation ajoutée.");
                }

                // Crée le graphique
                myChart = new Chart(chartCanvas, {
                    type: 'line',
                    data: {
                        labels: formattedLabels,
                        datasets: [{
                            label: capteurName,
                            data: data.values,
                            borderColor: '#212811',
                            backgroundColor: '#a9ca59',
                            tension: 0.3,
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            annotation: {
                                annotations: annotations
                            }
                        },
                        scales: {
                            x: {
                                title: { display: true, text: "Date" },
                                ticks: { autoSkip: true, maxTicksLimit: 10 }
                            },
                            y: {
                                title: { display: true, text: "Valeur" }
                            }
                        }
                    },
                    plugins: [Chart.registry.getPlugin('annotation')]
                });

                // Ouvre le modal avec le bon titre
                document.getElementById("graphModalLabel").textContent = `Graphique : ${capteurName}`;
                new bootstrap.Modal(document.getElementById("graphModal")).show();

            } catch (error) {
                console.error("Erreur lors du chargement du graphique :", error);
                alert("❌ Une erreur est survenue :\n" + error.message);
            }
        });
    });
});
