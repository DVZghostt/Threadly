<nav class="navbar navbar-expand-lg bg-body-light">
    <div class="container-fluid">

        <!-- Logo en haut à gauche -->
        <a class="navbar-brand" href="index.php">
            <img src="include/img/logo.png" alt="Logo" width="50" height="50">
            <?php echo $page; ?>
        </a>

        <!-- Bouton burger (apparaît quand la page est petite) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Boutons connexion / créer un compte à droite -->
        <?php
            $adminMode = false;
            if (!empty($_SESSION['user_id'])) {
                $adminMode = isAdminUser($connection);
            }
            $cartCount = 0;
            if (!empty($_SESSION['cart']) && is_array($_SESSION['cart'])) {
                $cartCount = array_sum($_SESSION['cart']);
            }
        ?>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="catalogue.php">Catalogue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cart.php">Panier (<?= $cartCount ?>)</a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto align-items-center">
                <?php if ($adminMode) : ?>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-primary" href="admin_tshirts.php">Gérer les produits</a>
                </li>
                <li class="nav-item me-2">
                    <a class="btn btn-outline-success" href="admin_add_tshirt.php">Ajouter un habit</a>
                </li>
                <?php endif; ?>
                <?php if (!empty($_SESSION['login'])) : ?>
                <li class="nav-item me-3">
                    <span class="navbar-text">Bonjour, <?= htmlspecialchars($_SESSION['login'], ENT_QUOTES, 'UTF-8') ?></span>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-secondary" href="logout.php">Déconnexion</a>
                </li>
                <?php else : ?>
                <li class="nav-item">
                    <a class="btn btn-outline-primary me-2" href="connexion.php">Connexion</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-primary" href="creatAccount.php">Créer un compte</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</nav>       