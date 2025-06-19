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
    <link rel="stylesheet" href="Content/css/admin.css"/>
    <script src="Content/js/admin.js" defer></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Liste des utilisateurs</h2>
    <input type="text" id="searchInput" class="form-control mb-3" placeholder="Rechercher un utilisateur...">
    <div id="pagination" class="d-flex justify-content-center mt-3"></div>

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
                if (i === currentPage) btn.classList.add('numero_page');
                else btn.classList.add('numero_page_suivant');

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

    function toggleRole(userId, currentRole) {
        const newRole = currentRole === 'Admin' ? 'Visiteur' : 'Admin';
        const confirmMessage = `Voulez-vous vraiment changer le rôle en "${newRole}" ?`;

        if (confirm(confirmMessage)) {
            fetch("?controller=admin&action=toggle_role", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id: userId, role: newRole })
            })
                .then(response => {
                    console.log("Status:", response.status);
                    return response.text(); // Pas .json() pour mieux diagnostiquer
                })
                .then(text => {
                    console.log("Réponse brute du serveur:", text); // ← regarde ça dans la console
                    try {
                        const data = JSON.parse(text);
                        if (!data) {
                            throw new Error("Aucune donnée");
                        }
                        alert(data.message);
                        if (data.success) {
                            window.location.reload();
                        }
                    } catch (e) {
                        alert("Erreur : réponse JSON invalide.");
                        console.error("Erreur JSON:", e);
                        console.warn("Réponse brute:", text);
                    }
                })
                .catch(error => {
                    alert("Rôle mis a jour.");
                    console.error(error);
                });
        }
    }

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
