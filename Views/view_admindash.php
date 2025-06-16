<?php
require_once('Layout/header_horizontal.php');?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Content/css/admindash.css"/>
    <script src="Content/js/admindash.js" defer></script>
</head>
<body>
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card p-3 card-graph">
                     <div id="pagination" class=" mt-3">
                         <h2 class="card-title mb-3">Liste capteurs</h2>
                         <input type="text" id="searchCapteurs" class="form-control mb-2" placeholder="Filtrer les capteurs...">

                         <form action="?controller=admindash&action=add" method="POST" class="mb-3">
                             <input type="text" name="type" placeholder="Type" required>
                             <input type="text" name="name" placeholder="Nom" required>
                             <input type="text" name="unit" placeholder="Unité" required>
                             <button type="submit" class="btn add">Ajouter</button>
                         </form>

                         <table class="table table-bordered" id="capteursTable">
                         <thead>
                             <tr>
                                 <th>ID</th>
                                 <th>Type</th>
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
                                            data-id="<?= $capteur['id_sensor'] ?>"
                                            data-name="<?= htmlspecialchars($capteur['name']) ?>">
                                             <?= htmlspecialchars($capteur['id_sensor']) ?>
                                         </a>
                                     </td>
                                     <td><?= htmlspecialchars($capteur['type']) ?></td>
                                     <td><?= htmlspecialchars($capteur['name']) ?></td>
                                     <td><?= htmlspecialchars($capteur['unit']) ?></td>
                                     <td><?= isset($capteur['lim_min']) ? htmlspecialchars($capteur['lim_min']) : '—' ?></td>
                                     <td><?= isset($capteur['lim_max']) ? htmlspecialchars($capteur['lim_max']) : '—' ?></td>
                                     <td>
                                         <a href="?controller=admindash&action=delete&id_sensor=<?= $capteur['id_sensor'] ?>"
                                            onclick="return confirm('Supprimer ce capteur ?')"
                                            class="btn btn-sm supp">
                                             <i class="fas fa-trash"></i>
                                         </a>
                                         <button class="btn btn-sm modifierLimites"
                                                 data-id="<?= $capteur['id_sensor'] ?>"
                                                 data-min="<?= $capteur['lim_min'] ?>"
                                                 data-max="<?= $capteur['lim_max'] ?>"
                                                 data-name="<?= htmlspecialchars($capteur['name']) ?>">
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

        <div class="col-md-4">
            <div class="card p-3 card-graph">
                <div id="pagination" class=" mt-3">
                    <h2 class="card-title mb-3">Liste actionneurs</h2>
                    <input type="text" id="searchActionneurs" class="form-control mb-2" placeholder="Filtrer les actionneurs...">
                    <form action="?controller=admindash&action=add_actuator" method="POST" class="actionneur mb-3">
                        <input type="text" name="type" placeholder="Type" required>
                        <input type="text" name="name" placeholder="Nom" required>
                        <input type="text" name="state" placeholder="État" required>
                        <button type="submit" class="btn add">Ajouter</button>
                    </form>

                    <table class="table table-striped" id="actionneursTable">
                    <thead class="table_att">
                        <tr>
                            <th>Numéro</th>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Unit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data['actionneurs'] as $actionneurs): ?>
                            <tr>
                                <td><?= htmlspecialchars($actionneurs['id_actuator']) ?></td>
                                <td><?= htmlspecialchars($actionneurs['type']) ?></td>
                                <td><?= htmlspecialchars($actionneurs['name']) ?></td>
                                <td><?= htmlspecialchars($actionneurs['state']) ?></td>

                                <td>
                                    <a href="?controller=admindash&action=delete_actuator&id_actuator=<?= $actionneurs['id_actuator'] ?>"
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
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="graphModalLabel">Graphique du capteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <canvas id="capteurChart" style="height: 300px; width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Exemple bouton pour ouvrir le graph -->
<button class="voirGraph btn btn-primary" data-id="1" data-name="Température Salon">Voir Graph</button>

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
                    <input type="hidden" name="id_sensor" id="modal-id-sensor">
                    <p><strong id="sensor-name"></strong></p>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
