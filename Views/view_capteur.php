<?php
require_once('Layout/header_horizontal.php');?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!--    <script src="Content/js/capteur.js" defer></script>-->
    <link rel="stylesheet" href="Content/css/capteur.css">
</head>
<body>

<div class="container" >

    <h1 class="mb-4 mt-4 text-center" >Tableau de bord capteurs</h1>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-3">
                <div class="d-flex justify-content-evenly text-center">
                    <div style="min-width: 120px;">
                        <div>Température à Paris</div>
                        <h3 id="parisTemp" class="valeur_resum"> <?php echo $temperatureInterieure?> °C</h3>
                    </div>
                    <div style="min-width: 120px;">
                        <div>Température</div>
                        <h3 id="resumeTemp" class="valeur_resum">  <?= isset($temp) ? htmlspecialchars($temp) . " °C" : "-- °C" ?>
                        </h3>
                    </div>
                    <div style="min-width: 120px;">
                        <div>Luminosite</div>
                        <h3 id="resumeGaz" class="valeur_resum">  <?= isset($lum) ? htmlspecialchars($lum) . " %" : "-- %" ?></h3>
                    </div>
                    <div style="min-width: 120px;">
                        <div>Humidite</div>
                        <h3 id="resumeLum" class="valeur_resum">  <?= isset($hum) ? htmlspecialchars($hum) . " %" : "-- %" ?>
                        </h3>
                    </div>
                    <div style="min-width: 120px;">
                        <div>Humidite Sol</div>
                        <h3 id="resumeLum" class="valeur_resum">  <?= isset($hum_sol) ? htmlspecialchars($hum_sol) . " %" : "-- %" ?>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Graphique + Actionneurs côte à côte -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card p-3 card-graph">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Température</h5>
                    <button class="btn voirPlus btn-sm" data-id="2" data-nom="Température">Voir plus</button>
                </div>
                <canvas id="tempHumChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>

        <div class="col-md-4">
            <div id="actionneursContainer" class="card p-3" style="height:380px">
                <h5 class="card-title">Actionneurs (état actuel)</h5>
                <ul id="actionneursList" class="list-group list-group-flush mt-3">
                    <?php if (!empty($actionneurs)) : ?>
                        <?php foreach ($actionneurs as $act) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= htmlspecialchars($act['name']) ?>
                                <span class="badge bg-<?= $act['state'] ? 'success' : 'secondary' ?>">
                            <?= $act['state'] ? 'Activé' : 'Désactivé' ?>
                        </span>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li class="list-group-item">Aucun actionneur trouvé.</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

    </div>

    <!-- Deux autres graphiques côte à côte -->
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 card-graph">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Luminosité</h5>
                    <button class="btn voirPlusLum btn-sm" data-bs-toggle="modal" data-bs-target="#modalLuminosite" data-id="1" data-nom="Luminosité">
                        Voir plus
                    </button>
                </div>
                <canvas id="luminositeChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 card-graph">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Humidité (sol & air)</h5>
                    <button class="btn voirPlus btn-sm" data-bs-toggle="modal" data-bs-target="#modalHumidite" data-id="3">
                        Voir plus
                    </button>
                </div>
                <canvas id="humiditeSolChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>

    </div>

    <div class="modal fade" id="graphModal" tabindex="-1" aria-labelledby="graphModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="graphModalLabel">Graphique</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <canvas id="capteurChart" width="800" height="400"></canvas>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalLuminosite" tabindex="-1" aria-labelledby="modalLuminositeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLuminositeLabel">Graphique Luminosité</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <canvas id="luminositeModalChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHumidite" tabindex="-1" aria-labelledby="modalHumiditeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalHumiditeLabel">Graphique Humidité (sol & air)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <canvas id="humiditeModalChart" style="height: 300px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Injection PHP des données
    const tempHumData = <?= json_encode($tempHumData) ?>;

    const labels = tempHumData.map(item => {
        const date = new Date(item.date_heure);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    });
    const dataValues = tempHumData.map(item => item.valeur);

    const ctx = document.getElementById('tempHumChart').getContext('2d');

    const tempHumChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Température (°C)',
                data: dataValues,
                borderColor: '#768D3E',
                backgroundColor: 'rgba(0, 0, 0, 0.1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'category',
                    title: {
                        display: true,
                        text: 'Date / Heure'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Température (°C)'
                    },
                    beginAtZero: false
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('graphModal');
        const modalInstance = new bootstrap.Modal(modalElement);
        const ctxModal = document.getElementById('capteurChart').getContext('2d');
        let modalChart = null;
        const labels = tempHumData.map(item => {
            const date = new Date(item.date_heure);
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        });

        const graphsData = {
            "2": {
                labels: labels,
                values: dataValues
            }
        };

        function drawModalChart(id, nom) {
            const data = graphsData[id];
            if (!data) {
                alert('Données du graphique introuvables.');
                return;
            }

            if (modalChart) modalChart.destroy();

            modalChart = new Chart(ctxModal, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: nom,
                        data: data.values,
                        borderColor: '#212811',
                        backgroundColor: 'rgba(169, 202, 89, 0.5)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Heure' } },
                        y: { title: { display: true, text: 'Valeur' } }
                    }
                }
            });
        }

        document.querySelectorAll('.voirPlus').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nom = button.dataset.nom || button.closest('.card').querySelector('h5').textContent;
                drawModalChart(id, nom);
                modalInstance.show();
            });
        });
    });

    //luminosite
    // Injection PHP des données luminosité
    const luminositeData = <?= json_encode($luminositeData) ?>;

    // Préparation des labels et valeurs pour le graphique principal
    const labelsLum = luminositeData.map(item => {
        const date = new Date(item.date_heure);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    });
    const dataLumValues = luminositeData.map(item => item.valeur);

    // Graphique principal luminosité
    const ctxLum = document.getElementById('luminositeChart').getContext('2d');
    const luminositeChart = new Chart(ctxLum, {
        type: 'line',
        data: {
            labels: labelsLum,
            datasets: [{
                label: 'Luminosité (%)',
                data: dataLumValues,
                borderColor: '#A9CA59',
                backgroundColor: 'rgba(255, 206, 86, 0.2)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'category',
                    title: { display: true, text: 'Date / Heure' }
                },
                y: {
                    title: { display: true, text: 'Luminosité (%)' },
                    beginAtZero: true
                }
            }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('modalLuminosite');
        const modalInstance = new bootstrap.Modal(modalElement);
        const ctxModalLum = document.getElementById('luminositeModalChart').getContext('2d');
        let modalLumChart = null;

        // Structure des données pour le modal (similaire à celle du principal)
        const graphsLumData = {
            "1": {
                labels: labelsLum,
                values: dataLumValues
            }
            // Ajoute d'autres id si besoin
        };

        function drawModalLumChart(id, nom) {
            const data = graphsLumData[id];
            if (!data) {
                alert('Données du graphique introuvables.');
                return;
            }

            if (modalLumChart) modalLumChart.destroy();

            modalLumChart = new Chart(ctxModalLum, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: nom,
                        data: data.values,
                        borderColor: '#212811',
                        backgroundColor: 'rgba(169, 202, 89, 0.5)',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Heure' } },
                        y: { title: { display: true, text: 'Valeur' } }
                    }
                }
            });
        }

        document.querySelectorAll('.voirPlusLum').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                const nom = button.dataset.nom || button.closest('.card').querySelector('h5').textContent;
                drawModalLumChart(id, nom);
                modalInstance.show();
            });
        });
    });


    //humidite
    const humiditeData = <?= json_encode($humidite) ?>;
    const humiditeSolData = <?= json_encode($humidite_sol) ?>;

    const labelsHumidite = humiditeSolData.map(item => {
        const date = new Date(item.date_heure);
        return `${date.getHours().toString().padStart(2,'0')}:${date.getMinutes().toString().padStart(2,'0')}`;
    });
    const valeursSol = humiditeSolData.map(item => item.valeur);
    const valeursHum = humiditeData.map(item => item.valeur);

    const ctxHumidite = document.getElementById('humiditeSolChart').getContext('2d');

    const humiditeChart = new Chart(ctxHumidite, {
        type: 'line',
        data: {
            labels: labelsHumidite,
            datasets: [
                {
                    label: 'Humidité du sol (%)',
                    data: valeursSol,
                    borderColor: '#768D3E',
                    backgroundColor: 'rgba(169, 202, 89, 0.2)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Humidité (%)',
                    data: valeursHum,
                    borderColor: '#A9CA59',
                    backgroundColor: 'rgba(118, 141, 62, 0.2)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: { type: 'category', title: { display: true, text: 'Date / Heure' }},
                y: { beginAtZero: true, max: 100, title: { display: true, text: 'Humidité (%)' }}
            }
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const modalElement = document.getElementById('modalHumidite');
        const modalInstance = new bootstrap.Modal(modalElement);
        const ctxModalHum = document.getElementById('humiditeModalChart').getContext('2d');
        let modalHumChart = null;

        // Données pour le modal
        const graphsHumData = {
            "3": {
                labels: labelsHumidite,
                datasets: [
                    {
                        label: 'Humidité du sol (%)',
                        data: valeursSol,
                        borderColor: '#768D3E',
                        backgroundColor: 'rgba(169, 202, 89, 0.2)',
                        fill: true,
                        tension: 0.3
                    },
                    {
                        label: 'Humidité (%)',
                        data: valeursHum,
                        borderColor: '#A9CA59',
                        backgroundColor: 'rgba(118, 141, 62, 0.2)',
                        fill: true,
                        tension: 0.3
                    }
                ]
            }
            // Ajoute d'autres id si besoin
        };

        function drawModalHumChart(id) {
            const data = graphsHumData[id];


            if (modalHumChart) modalHumChart.destroy();

            modalHumChart = new Chart(ctxModalHum, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: data.datasets
                },
                options: {
                    responsive: true,
                    scales: {
                        x: { title: { display: true, text: 'Date / Heure' }},
                        y: { beginAtZero: true, max: 100, title: { display: true, text: 'Humidité (%)' }}
                    }
                }
            });
        }

        document.querySelectorAll('.voirPlus').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.dataset.id;
                drawModalHumChart(id);
                modalInstance.show();
            });
        });
    });


    </script>


</body>
</html>
