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
                        <h3 id="resumeTemp" class="valeur_resum">-- °C</h3>
                    </div>
                    <div style="min-width: 120px;">
                        <div>Gaz</div>
                        <h3 id="resumeGaz" class="valeur_resum">-- ppm</h3>
                    </div>
                    <div style="min-width: 120px;">
                        <div>Luminosité</div>
                        <h3 id="resumeLum" class="valeur_resum">-- lux</h3>
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
                    <h5 class="card-title mb-0">Température / Humidité</h5>
                    <button class="btn voirPlus btn-sm" data-bs-toggle="modal" data-bs-target="#modalTempHum">
                        Voir plus
                    </button>
                </div>
                <canvas id="tempHumChart" style="width: 100%; height: 300px;"></canvas>
            </div>
        </div>

        <div class="col-md-4" >
            <div id="actionneursContainer" class="card p-3" style="height:380px">
                <h5 class="card-title">Actionneurs (état actuel)</h5>
                <ul id="actionneursList" class="list-group list-group-flush mt-3"></ul>
            </div>
        </div>
    </div>

    <!-- Deux autres graphiques côte à côte -->
    <div class="row">
        <div class="col-md-6">
            <div class="card p-3 card-graph">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Luminosité</h5>
                    <button class="btn voirPlus btn-sm" data-bs-toggle="modal" data-bs-target="#modalLuminosite">
                        Voir plus
                    </button>
                </div>
                <canvas id="luminositeChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 card-graph">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Concentration de gaz</h5>
                    <button class="btn voirPlus btn-sm" data-bs-toggle="modal" data-bs-target="#modalGaz">
                        Voir plus
                    </button>
                </div>
                <canvas id="humiditeSolChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>


<!-- Modal TempHum -->
<div class="modal fade" id="modalTempHum" tabindex="-1" aria-labelledby="modalTempHumLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 750px;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTempHumLabel">Température / Humidité - Vue étendue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" style="height: 10%;">
                <select id="tempHumPeriod" class="form-select mb-3" style="width: 150px;">
                    <option value="day" selected>Jour</option>
                    <option value="week">Semaine</option>
                    <option value="month">Mois</option>
                </select>
                <canvas id="humiditeSolChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Modal Luminosité -->
<div class="modal fade" id="modalLuminosite" tabindex="-1" aria-labelledby="modalLuminositeLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLuminositeLabel">Luminosité - Vue étendue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body" >
                <select id="lumPeriod" class="form-select mb-3" style="width: 150px;">
                    <option value="day" selected>Jour</option>
                    <option value="week">Semaine</option>
                    <option value="month">Mois</option>
                </select>
                <canvas id="luminositeChartLarge" style="height: 400px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Modal Gaz -->
<div class="modal fade" id="modalGaz" tabindex="-1" aria-labelledby="modalGazLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content" style="height: 750px;">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGazLabel">Gaz - Vue étendue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <select id="gazPeriod" class="form-select mb-3" style="width: 150px;">
                    <option value="day" selected>Jour</option>
                    <option value="week">Semaine</option>
                    <option value="month">Mois</option>
                </select>
                <canvas id="gazChartLarge" style="height: 400px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    const gazData = <?= json_encode($gazData) ?>;
    const actionneursState = <?= json_encode($actionneursState) ?>;
</script>

<script>
    // Injection PHP des données
    const tempHumData = <?= json_encode($tempHumData) ?>;

    // Extraction des labels (dates) et des valeurs
    const labels = tempHumData.map(item => {const date = new Date(item.date_heure);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    });
    const dataValues = tempHumData.map(item => item.valeur);

    // Récupération du contexte canvas
    const ctx = document.getElementById('tempHumChart').getContext('2d');

    // Création du graphique Chart.js
    const tempHumChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Température (°C)',
                data: dataValues,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
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


    // Injection PHP des données luminosité
    const luminositeData = <?= json_encode($luminositeData) ?>;

    // Extraction des labels (dates) et des valeurs
    const labelsLum = luminositeData.map(item => {const date = new Date(item.date_heure);
    const hours = date.getHours().toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
    });
    const dataLumValues = luminositeData.map(item => item.valeur);

    // Récupération du contexte canvas
    const ctxLum = document.getElementById('luminositeChart').getContext('2d');

    // Création du graphique Chart.js pour la luminosité
    const luminositeChart = new Chart(ctxLum, {
        type: 'line',
        data: {
            labels: labelsLum,
            datasets: [{
                label: 'Luminosité (lux)',
                data: dataLumValues,
                borderColor: 'rgba(255, 206, 86, 1)',
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
                    title: {
                        display: true,
                        text: 'Date / Heure'
                    }
                },
                y: {
                    title: {
                        display: true,
                        text: 'Luminosité (lux)'
                    },
                    beginAtZero: true
                }
            }
        }
    });

    const humiditeSolData = <?= json_encode($humidite_sol) ?>;

    // Préparation labels et valeurs
    const labelsHumiditeSol = humiditeSolData.map(item => {
        const date = new Date(item.date_heure);
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    });
    const dataValuesHumiditeSol = humiditeSolData.map(item => item.valeur);

    // Récupération du contexte canvas
    const ctxHumiditeSol = document.getElementById('humiditeSolChart').getContext('2d');

    // Création du graphique Chart.js
    const humiditeSolChart = new Chart(ctxHumiditeSol, {
        type: 'line',
        data: {
            labels: labelsHumiditeSol,
            datasets: [{
                label: 'Humidité du sol (%)',
                data: dataValuesHumiditeSol,
                borderColor: 'rgba(54, 162, 235, 1)',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
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
                    beginAtZero: true,
                    max: 100,
                    title: {
                        display: true,
                        text: 'Humidité du sol (%)'
                    }
                }
            }
        }
    });

</script>

</body>
</html>
