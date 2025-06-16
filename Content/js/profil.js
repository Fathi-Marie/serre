function deleteUser() {
    if (confirm("Voulez-vous vraiment désactiver votre compte ?")) {
        fetch("?controller=profil&action=delete_user", {
            method: "POST",
            headers: { "Content-Type": "application/json" }
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => {
                alert("Erreur lors de la désactivation.");
                console.error(error);
            });
    }
}
