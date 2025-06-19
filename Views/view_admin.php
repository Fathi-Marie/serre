<?php
require_once('Layout/header_horizontal.php');?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="Content/css/admin.css"/>
    <script src="Content/js/admin.js" defer></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Liste des utilisateurs</h2>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher un utilisateur...">
    <div id="pagination" class="d-flex justify-content-center mt-3" ></div>

    <table class="table table-striped" id="usersTable">
        <thead class="table_att">
        <tr>
            <th>ID</th>
            <th>Logo</th>
            <th>Mail</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Numéro</th>
            <th>Rôle</th>
            <th>Créé le</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['users'] as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user['id']) ?></td>
                <td>
                    <div class="rounded-circle text-white text-center"
                         style="width: 40px; height: 40px; line-height: 40px; background-color: #768D3E">
                        <?= htmlspecialchars(mb_substr($user['nom'], 0, 1)) ?>
                    </div>
                </td>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= htmlspecialchars($user['nom']) ?></td>
                <td><?= htmlspecialchars($user['prénom']) ?></td>
                <td><i class="fas fa-phone me-2"></i><?= htmlspecialchars($user['telephone'] ?? 'N/A') ?></td>
                <td><?= htmlspecialchars(implode(', ', $user['roles'])) ?></td>
                <td><?= htmlspecialchars($user['creer_a']) ?></td>
                <td>
                    <button
                            class="btn btn-sm me-1"
                            title="Modifier"
                            style="background-color: #768D3E; color: white"
                            onclick='toggleRole(<?= json_encode($user["id"]) ?>, <?= json_encode($user["roles"][0]) ?>)'
                    >
                        <i class="fas fa-pen"></i>
                    </button>

                    <button
                            class="delete button"
                            title="Supprimer"
                            onclick="disableUser(<?= $user['id'] ?>)">
                        <i class="fas fa-trash"></i>
                    </button>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    function disableUser(userId) {
        if (!confirm("Voulez-vous vraiment désactiver cet utilisateur ?")) {
            return;
        }

        fetch("?controller=admin&action=delete_user", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: userId })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // Optionnel : mettre à jour la ligne utilisateur dans le tableau
                    const userRow = document.getElementById(`user-row-${userId}`);
                    if (userRow) {
                        userRow.style.opacity = "0.5";
                        const etatCell = document.getElementById(`etat-user-${userId}`);
                        if (etatCell) etatCell.textContent = "inactif";
                    }
                } else {
                    alert("Erreur lors de la désactivation de l'utilisateur.");
                }
            })
            .catch(error => {
                console.error(error);
                alert("Erreur réseau lors de la désactivation.");
            });
    }

</script>
</body>
</html>
