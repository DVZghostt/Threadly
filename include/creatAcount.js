function afficherNotif(message, succes, ancreId = 'btnCreation') {
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

    const ancre = document.getElementById(ancreId)
    ancre.insertAdjacentElement('afterend', notif)

    setTimeout(() => notif.remove(), 3000)
}

function validerFormulaire(login, password, passwordVerif) {
    const regexMail  = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    const regexNom   = /^[a-zA-ZÀ-ÿ '-]{2,50}$/
    const regexLogin = /^[a-zA-Z0-9._-]{3,30}$/
    const regexMdp   = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/

    // Login : nom, pseudo ou mail
    const loginValide = regexMail.test(login) || regexNom.test(login) || regexLogin.test(login)
    if (!loginValide) {
        afficherNotif("❌ Login invalide (pseudo, nom ou email attendu)", false)
        return false
    }

    // Mot de passe
    if (!regexMdp.test(password)) {
        afficherNotif("❌ Mot de passe trop faible (8 car. min, maj, min, chiffre, symbole)", false)
        return false
    }

    // Confirmation mot de passe
    if (password !== passwordVerif) {
        afficherNotif("❌ Les mots de passe ne correspondent pas", false)
        return false
    }

    return true
}

function creatAccount() {
    const login         = document.getElementById('login').value.trim()
    const password      = document.getElementById('password').value
    const passwordVerif = document.getElementById('passwordVerif').value

    if (!validerFormulaire(login, password, passwordVerif)) return

    const data = { login, password }

    fetch("crud/add/addAcount.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            afficherNotif("✅ Compte créé avec succès !", true)
            setTimeout(() => window.location.href = 'index.php', 900)
        } else {
            afficherNotif("❌ " + (data.message || "Erreur lors de la création"), false)
        }
    })
    .catch(error => {
        console.error("Erreur :", error)
        afficherNotif("❌ Erreur de connexion au serveur", false)
    })
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById("btnCreation").addEventListener('click', creatAccount)
})