function afficherNotif(message, succes) {
    const ancienne = document.getElementById('notif')
    if (ancienne) ancienne.remove()

    const notif = document.createElement('div')
    notif.id = 'notif'
    notif.textContent = message
    notif.style.cssText = `
        padding: 10px 20px;
        margin-top: 10px;
        border-radius: 6px;
        font-weight: bold;
        text-align: center;
        background-color: ${succes ? '#d4edda' : '#f8d7da'};
        color: ${succes ? '#155724' : '#721c24'};
        border: 1px solid ${succes ? '#c3e6cb' : '#f5c6cb'};
    `

    const btn = document.getElementById('btnConnexion')
    btn.insertAdjacentElement('afterend', notif)

    setTimeout(() => notif.remove(), 3000)
}

function connexion(){
    const login = document.getElementById('login').value
    const password = document.getElementById('password').value

    fetch("crud/read/readConnexion.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ login: login, password: password })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            afficherNotif("✅ Connexion réussie !", true)
            setTimeout(() => window.location.href = 'index.php', 700)
        } else {
            afficherNotif("❌ " + (data.message || "Identifiants incorrects"), false)
        }
    })
    .catch(error => {
        console.error("Erreur :", error)
        afficherNotif("❌ Erreur de connexion au serveur", false)
    })
}

// DOMContentLoaded en dernier
document.addEventListener('DOMContentLoaded', function(){
    document.getElementById("btnConnexion").addEventListener('click', connexion)
})