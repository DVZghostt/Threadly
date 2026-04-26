<?php

$page = "Connexion";
require_once 'include/connect.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadly — Connexion</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h3 mb-4 text-center">Connexion</h1>
                    <form>
                        <div class="mb-3">
                            <label class="form-label" for="login">Nom d'utilisateur / E-Mail</label>
                            <input type="text" class="form-control" id="login" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Mot de passe</label>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                        <button id="btnConnexion" type="button" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
<script src="include/connexion.js"></script>
</body>
</html>
