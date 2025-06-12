<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="Content/js/capteur.js" defer></script>
    <link rel="stylesheet" href="Content/css/capteur.css">
</head>
<body>
<h1 class="mb-4">Dashboard Capteurs</h1>

<div class="container">
    <div class="row g-3">

        <!-- Température/Humidité -->
        <div class="col-6">
            <div class="card p-3" style="height: 450px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Température / Humidité</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTempHum">
                        Voir en grand
                    </button>
                </div>
                <canvas id="tempHumChart" style="height: 250px; width: 100%;"></canvas>
            </div>
        </div>

        <!-- Luminosité -->
        <div class="col-6">
            <div class="card p-3" style="height: 450px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Luminosité</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalLuminosite">
                        Voir en grand
                    </button>
                </div>
                <canvas id="luminositeChart" style="height: 150px; width: 100%;"></canvas>
            </div>
        </div>

        <!-- Gaz (Jauge) -->
        <div class="col-12">
            <div class="card p-3" style="height: 150px;">
                <h5 class="card-title mb-3">Résumé des dernières mesures</h5>
                <div class="d-flex justify-content-around text-center">
                    <div>
                        <div>Température</div>
                        <div id="resumeTemp" class="fw-bold px-2 py-1 rounded">--</div>
                    </div>
                    <div>
                        <div>Gaz</div>
                        <div id="resumeGaz" class="fw-bold px-2 py-1 rounded">--</div>
                    </div>
                    <div>
                        <div>Luminosité</div>
                        <div id="resumeLum" class="fw-bold px-2 py-1 rounded">--</div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Actionneurs -->
        <div class="col-6">
            <div class="card p-3" style="height: 350px; overflow-y: auto;">
                <h5 class="card-title">Actionneurs (état actuel)</h5>
                <ul id="actionneursList" class="list-group list-group-flush mt-3"></ul>
            </div>
        </div>
        <!-- Gaz (Graphique) -->
        <div class="col-6">
            <div class="card p-3" style="height: 450px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Concentration de gaz</h5>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalGaz">
                        Voir en grand
                    </button>
                </div>
                <canvas id="gazChart" style="height: 250px; width: 100%;"></canvas>
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
                <canvas id="tempHumChartLarge" style="width: 100%;"></canvas>
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
    const tempHumData = <?= json_encode($tempHumData) ?>;
    const luminositeData = <?= json_encode($luminositeData) ?>;
    const gazData = <?= json_encode($gazData) ?>;
    const actionneursState = <?= json_encode($actionneursState) ?>;
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>