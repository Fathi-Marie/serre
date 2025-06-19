<?php
require_once('Layout/header_horizontal.php');?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Content/css/admindash.css"/>
    <script src="Content/js/admindash.js" defer></script>
</head>
<body>
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3 card-graph">
                     <div id="pagination" class=" mt-3">
                         <h2 class="card-title mb-3">Liste capteurs</h2>
                         <input type="text" id="searchCapteurs" class="form-control mb-2" placeholder="Filtrer les capteurs...">

                         <form action="?controller=admindash&action=add" method="POST" class="mb-3">
                             <input type="text" name="nom" placeholder="Nom" required>
                             <input type="text" name="unite" placeholder="Unité" required>
                             <select name="is_actif" required class="actif">
                                 <option value="1">Actif</option>
                                 <option value="0">Inactif</option>
                             </select>

                             <button type="submit" class="btn add">Ajouter</button>
                         </form>

                         <table class="table table-bordered" id="capteursTable">
                         <thead>
                             <tr>
                                 <th>ID</th>
                                 <th>Nom</th>
                                 <th>Unité</th>
                                 <th>Limite Min</th>
                                 <th>Limite Max</th>
                                 <th>Actions</th>
                             </tr>
                             </thead>
                             <tbody>
                             <?php foreach ($capteurs as $capteur): ?>
                                 <tr>
                                     <td style="background-color: #ffffff;">
                                         <a href="#" class="voirGraph btn btn-sm btn-info"
                                            data-id="<?= $capteur['id'] ?>"
                                            data-nom="<?= htmlspecialchars($capteur['nom']) ?>">
                                             <?= htmlspecialchars($capteur['id']) ?>
                                         </a>
                                     </td>
                                     <td><?= htmlspecialchars($capteur['nom']) ?></td>
                                     <td><?= htmlspecialchars($capteur['unite'] ?? '') ?></td>
                                     <td><?= isset($capteur['lim_min']) ? htmlspecialchars($capteur['lim_min'] ?? '') : '—' ?></td>
                                     <td><?= isset($capteur['lim_max']) ? htmlspecialchars($capteur['lim_max']) : '—' ?></td>
                                     <td>
                                         <a href="?controller=admindash&action=delete&id=<?= $capteur['id'] ?>"
                                            onclick="return confirm('Supprimer ce capteur ?')"
                                            class="btn btn-sm supp">
                                             <i class="fas fa-trash"></i>
                                         </a>
                                         <button class="btn btn-sm modifierLimites"
                                                 data-id="<?= $capteur['id'] ?>"
                                                 data-min="<?= $capteur['lim_min'] ?>"
                                                 data-max="<?= $capteur['lim_max'] ?>"
                                                 data-nom="<?= htmlspecialchars($capteur['nom']) ?>">
                                             <i class="fas fa-pen"></i>
                                         </button>
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                             </tbody>
                         </table>
                     </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-3 card-graph">
                <div id="pagination" class=" mt-3">
                    <h2 class="card-title mb-3">Liste actionneurs</h2>
                    <input type="text" id="searchActionneurs" class="form-control mb-2" placeholder="Filtrer les actionneurs...">
                    <form action="?controller=admindash&action=add_actuator" method="POST" class="actionneur mb-3">
                        <input type="text" name="type" placeholder="Type" required>
                        <select class="actif" name="etat" required>
                            <option value="1">Actif</option>
                            <option value="0">Inactif</option>
                        </select>
                        <button type="submit" class="btn add">Ajouter</button>
                    </form>

                    <table class="table table-striped" id="actionneursTable">
                    <thead class="table_att">
                        <tr>
                            <th>Numéro</th>
                            <th>Type</th>
                            <th>Etat</th>
                            <th>Date heure</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['actionneurs'] as $actionneur): ?>
                            <tr>
                                <td><?= htmlspecialchars($actionneur['id_actuator']) ?></td>
                                <td><?= htmlspecialchars($actionneur['name']) ?></td>
                                <td><?= htmlspecialchars($actionneur['state'] ?? '') ?></td>
                                <td><?= htmlspecialchars($actionneur['date_heure'] ?? '') ?></td>
                                <td>
                                    <a href="?controller=admindash&action=delete_actuator&id=<?= $actionneur['id_actuator'] ?>"
                                       onclick="return confirm('Supprimer cet actionneur ?')"
                                       class="btn btn-sm supp">
                                        Supprimer
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Bootstrap CSS + JS (via CDN pour le modal) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js + annotation plugin -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1.4.0"></script>

<!-- Modal -->
<div class="modal fade" id="graphModal" tabindex="-1" aria-labelledby="graphModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="graphModalLabel">Graphique</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div id="navigationJour" style="display: flex; align-items: center; justify-content: center; margin: 10px;">
                    <button id="prevDay" class="btn btn-sm btn-secondary">←</button>
                    <span id="jourAffiche" style="margin: 0 10px; font-weight: bold;"></span>
                    <button id="nextDay" class="btn btn-sm btn-secondary">→</button>
                </div>

                <select id="periodeSelect" class="form-select form-select-sm mb-3" style="width:150px;">
                    <option value="jour" selected>Par jour</option>
                    <option value="semaine">Par semaine</option>
                </select>

                <!-- Canvas pour le graphique -->
                <canvas id="capteurChart" width="800" height="400"></canvas>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>



<!-- Modal Modification des Limites -->
<div class="modal fade" id="limiteModal" tabindex="-1" aria-labelledby="limiteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="post" action="?controller=admindash&action=updateLimites">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="limiteModalLabel">Modifier les limites</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="modal-id-sensor">
                    <p><strong id="sensor-nom"></strong></p>
                    <div class="mb-3">
                        <label for="lim_min" class="form-label">Limite min</label>
                        <input type="number" step="any" class="form-control" id="lim_min" name="lim_min">
                    </div>
                    <div class="mb-3">
                        <label for="lim_max" class="form-label">Limite max</label>
                        <input type="number" step="any" class="form-control" id="lim_max" name="lim_max">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        makeTableSortable("capteursTable");
        makeTableSortable("actionneursTable");

        setupFilter("searchCapteurs", "capteursTable");
        setupFilter("searchActionneurs", "actionneursTable");

        const chartCanvas = document.getElementById("capteurChart");
        let myChart = null;

        const periodeSelect = document.getElementById("periodeSelect");
        let currentCapteurId = null;
        let currentCapteurName = null;

        function formatLabelsEtValues(data, periode) {
            const nomsJours = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'];

            if (periode === "jour") {
                const labels = data.labels.map(dateStr => {
                    const d = new Date(dateStr);
                    const jour = String(d.getDate()).padStart(2, '0');
                    const mois = String(d.getMonth() + 1).padStart(2, '0');
                    const annee = d.getFullYear();
                    return `${jour}/${mois}/${annee}`;
                });
                return { labels, values: data.values };
            } else if (periode === "semaine") {
                // Trouve le lundi de la semaine de la première date
                const dates = data.labels.map(d => new Date(d));
                const premiereDate = dates[0];
                const jourSemaine = premiereDate.getDay();
                const diffLundi = (jourSemaine === 0 ? -6 : 1) - jourSemaine;
                const lundi = new Date(premiereDate);
                lundi.setDate(premiereDate.getDate() + diffLundi);

                // Génère du lundi au vendredi
                let semaineComplete = [];
                for(let i = 0; i < 5; i++) {
                    let d = new Date(lundi);
                    d.setDate(lundi.getDate() + i);
                    semaineComplete.push(d);
                }

                // Format labels
                const labels = semaineComplete.map(d => {
                    const nomJour = nomsJours[d.getDay()];
                    const jour = String(d.getDate()).padStart(2, '0');
                    const mois = String(d.getMonth() + 1).padStart(2, '0');
                    return `${nomJour} ${jour}/${mois}`;
                });

                // Aligne les valeurs sur la semaine complète, met null si pas de valeur
                const values = semaineComplete.map(d => {
                    const index = data.labels.findIndex(dateStr => new Date(dateStr).toDateString() === d.toDateString());
                    return index !== -1 ? data.values[index] : null;
                });

                return { labels, values };
            }

        }
        function getWeekNumber(date) {
            const d = new Date(date);
            d.setHours(0, 0, 0, 0);
            d.setDate(d.getDate() + 4 - (d.getDay() || 7));
            const yearStart = new Date(d.getFullYear(), 0, 1);
            const weekNo = Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
            return `${d.getFullYear()}-W${String(weekNo).padStart(2, '0')}`;
        }

        async function loadGraph(capteurId, capteurName, periode) {
            try {
                const response = await fetch(`?controller=admindash&action=get_graph_data&id=${capteurId}&periode=${periode}`);

                if (!response.ok) throw new Error(`Erreur HTTP ${response.status} - ${response.statusText}`);

                const data = await response.json();

                if (data.error) throw new Error(data.error);

                if (!data || !Array.isArray(data.labels) || !Array.isArray(data.values)) {
                    throw new Error("Structure des données invalide reçue du serveur.");
                }

                const limMax = parseFloat(data.lim_max);

                const { labels: formattedLabels, values: formattedValues } = formatLabelsEtValues(data, periode);

                if (myChart) myChart.destroy();

                const annotations = {};
                if (!isNaN(limMax)) {
                    annotations.limiteMax = {
                        type: 'line',
                        yMin: limMax,
                        yMax: limMax,
                        xMin: 0,
                        xMax: formattedLabels.length - 1,
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
                }

                myChart = new Chart(chartCanvas, {
                    type: 'line',
                    data: {
                        labels: formattedLabels,
                        datasets: [{
                            label: capteurName,
                            data: formattedValues,
                            borderColor: '#212811',
                            backgroundColor: '#a9ca59',
                            tension: 0.3,
                            fill: true,
                            spanGaps: true // pour connecter les points même s'il y a des nulls
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

                document.getElementById("graphModalLabel").textContent = `Graphique : ${capteurName}`;
                if (!document.getElementById("graphModal").classList.contains('show')) {
                    new bootstrap.Modal(document.getElementById("graphModal")).show();
                }
                if (periode === "jour" || periode === "semaine") {
                    dataDuMois = data.labels.map((label, i) => ({
                        label: label,
                        value: data.values[i]
                    }));

                    datesUniques = [...new Set(dataDuMois.map(d => new Date(d.label).toISOString().split("T")[0]))];
                    currentDateIndex = 0;

                    semainesUniques = [...new Set(dataDuMois.map(d => getWeekNumber(new Date(d.label))))];
                    currentWeekIndex = 0;

                    if (periode === "jour") afficherJour(currentDateIndex);
                    else afficherSemaine(currentWeekIndex);
                }
            } catch (error) {
                console.error("Erreur lors du chargement du graphique :", error);
            }

        }

        document.querySelectorAll(".voirGraph").forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                currentCapteurId = this.dataset.id;
                currentCapteurName = this.dataset.nom;
                loadGraph(currentCapteurId, currentCapteurName, periodeSelect.value);
            });
        });

        periodeSelect.addEventListener("change", function () {
            if (currentCapteurId && currentCapteurName) {
                loadGraph(currentCapteurId, currentCapteurName, this.value);
            }
        });
    });



    let currentDateIndex = 0;
    let datesUniques = [];
    let dataDuMois = [];
    let semainesUniques = [];
    let currentWeekIndex = 0;

    function formatDateFr(date) {
        return `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth()+1).padStart(2,'0')}/${date.getFullYear()}`;
    }

    function afficherJour(index) {
        const dateCible = datesUniques[index];
        const quarts = [
            { label: "00h-06h", start: 0, end: 6 },
            { label: "06h-12h", start: 6, end: 12 },
            { label: "12h-18h", start: 12, end: 18 },
            { label: "18h-24h", start: 18, end: 24 },
        ];
        let valeursParQuart = quarts.map(() => []);

        dataDuMois.forEach((entry, i) => {
            const d = new Date(entry.label);
            const dateStr = d.toISOString().split("T")[0];
            const h = d.getHours();
            const valeur = entry.value;

            if (dateStr === dateCible) {
                for (let i = 0; i < quarts.length; i++) {
                    if (h >= quarts[i].start && h < quarts[i].end) {
                        valeursParQuart[i].push(valeur);
                        break;
                    }
                }
            }
        });


        function regrouperParSemaine(data) {
            const grouped = {};
            data.forEach((entry) => {
                const d = new Date(entry.label);
                const semaine = getWeekNumber(d);
                if (!grouped[semaine]) grouped[semaine] = [];
                grouped[semaine].push(entry.value);
            });

            semainesUniques = Object.keys(grouped);

            return semainesUniques.map(semaine => {
                const valeurs = grouped[semaine];
                const moyenne = valeurs.reduce((a, b) => a + b, 0) / valeurs.length;
                return { semaine, moyenne };
            });
        }

        const labels = quarts.map(q => q.label);
        const values = valeursParQuart.map(arr => {
            if (arr.length === 0) return null;
            const sum = arr.reduce((a, b) => a + b, 0);
            return sum / arr.length;
        });

        document.getElementById("jourAffiche").textContent = formatDateFr(new Date(dateCible));

        if (myChart) myChart.destroy();
        myChart = new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: currentCapteurName,
                    data: values,
                    borderColor: '#212811',
                    backgroundColor: '#a9ca59',
                    tension: 0.3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    annotation: { annotations: {} }
                },
                scales: {
                    x: { title: { display: true, text: "Quart de journée" } },
                    y: { title: { display: true, text: "Valeur" } }
                }
            }
        });
    }
    function afficherSemaine(index) {
        const semaine = semainesUniques[index];
        const groupe = regrouperParSemaine(dataDuMois).find(s => s.semaine === semaine);

        document.getElementById("jourAffiche").textContent = `Semaine ${semaine}`;

        if (myChart) myChart.destroy();
        myChart = new Chart(chartCanvas, {
            type: 'bar',
            data: {
                labels: [`${semaine}`],
                datasets: [{
                    label: currentCapteurName,
                    data: [groupe.moyenne],
                    backgroundColor: '#a9ca59',
                    borderColor: '#212811',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: { title: { display: true, text: "Semaine" }},
                    y: { title: { display: true, text: "Valeur moyenne" }}
                }
            }
        });
    }
    function naviguerPeriode(direction) {
        const periode = periodeSelect.value;
        if (periode === "jour") {
            if (direction === "prev" && currentDateIndex > 0) {
                currentDateIndex--;
                afficherJour(currentDateIndex);
            } else if (direction === "next" && currentDateIndex < datesUniques.length - 1) {
                currentDateIndex++;
                afficherJour(currentDateIndex);
            }
        } else if (periode === "semaine") {
            if (direction === "prev" && currentWeekIndex > 0) {
                currentWeekIndex--;
                afficherSemaine(currentWeekIndex);
            } else if (direction === "next" && currentWeekIndex < semainesUniques.length - 1) {
                currentWeekIndex++;
                afficherSemaine(currentWeekIndex);
            }
        }
    }

    document.getElementById("prevDay").addEventListener("click", () => {
        naviguerPeriode("prev");
    });
    document.getElementById("nextDay").addEventListener("click", () => {
        naviguerPeriode("next");
    });


</script>
</body>
</html>
