<?php
require_once('Layout/view_header.php');?>
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Eclosia - Accueil</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="Content/css/accueil.css">
</head>
<body>

<section class="hero">
    <h1>Bienvenue chez Eclosia</h1>
    <p>Surveillez, automatisez et faites grandir vos environnements vivants</p>
    <a href="?controller=connexion&action=connexionController" class="btn btn-eclosia">Accéder au Tableau de Bord</a>
</section>

<section class="section">
    <div class="container">
        <h2 class="text-center mb-5">Nos Capteurs & Actionneurs</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="feature-card">
                    <i class="fas fa-thermometer-half fa-2x mb-3"></i>
                    <h5>Température</h5>
                    <p>Mesurez et régulez la chaleur ambiante avec précision.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card">
                    <i class="fas fa-sun fa-2x mb-3"></i>
                    <h5>Luminosité</h5>
                    <p>Optimisez la lumière pour vos cultures ou vos espaces.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card">
                    <i class="fas fa-smog fa-2x mb-3"></i>
                    <h5>Gaz</h5>
                    <p>Détectez les gaz nocifs ou contrôlez la qualité de l'air.</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="feature-card">
                    <i class="fas fa-robot fa-2x mb-3"></i>
                    <h5>Actionneurs</h5>
                    <p>Automatisez les réactions : ventilation, arrosage, etc.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section deuxieme">
    <div class="container">
        <h2 class="text-center text-white ">Aperçu en direct</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <canvas id="tempChart"></canvas>
            </div>
            <div class="col-md-4">
                <canvas id="lightChart"></canvas>
            </div>
            <div class="col-md-4">
                <canvas id="gasChart"></canvas>
            </div>
        </div>
    </div>
</section>
<script>

</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const options = {
        type: 'line',
        options: {
            responsive: true,
            plugins: {
                legend: { labels: { color: 'white' } },
            },
            scales: {
                x: { ticks: { color: 'white' }, grid: { color: '#333' } },
                y: { ticks: { color: 'white' }, grid: { color: '#333' } },
            }
        }
    };

    new Chart(document.getElementById('tempChart'), {
        ...options,
        data: {
            labels: ['8h', '10h', '12h', '14h', '16h'],
            datasets: [{
                label: 'Température (°C)',
                data: [18, 20, 22, 24, 23],
                borderColor: '#768D3E',
                backgroundColor: 'rgba(118, 141, 62, 0.3)',
                fill: true,
            }]
        }
    });

    new Chart(document.getElementById('lightChart'), {
        ...options,
        data: {
            labels: ['8h', '10h', '12h', '14h', '16h'],
            datasets: [{
                label: 'Luminosité (%)',
                data: [30, 45, 70, 80, 60],
                borderColor: '#f1c40f',
                backgroundColor: 'rgba(118, 141, 62, 0.3)',
                fill: true,
            }]
        }
    });

    new Chart(document.getElementById('gasChart'), {
        ...options,
        data: {
            labels: ['8h', '10h', '12h', '14h', '16h'],
            datasets: [{
                label: 'Gaz détecté (ppm)',
                data: [200, 210, 250, 220, 190],
                borderColor: '#768D3E',
                backgroundColor: 'rgba(241, 196, 15, 0.3)',
                fill: true,
            }]
        }
    });
</script>
<?php
require_once('Layout/footer.php');
?>
