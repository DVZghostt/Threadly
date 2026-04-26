<?php
$page = "Gestion des produits";
require_once 'include/connect.php';

if (empty($_SESSION['user_id']) || !isAdminUser($connection)) {
    header('Location: index.php');
    exit;
}

$success = isset($_GET['success']);
$deleted = isset($_GET['deleted']);
$error = trim($_GET['error'] ?? '');

$tshirts = [];
try {
    $stmt = $connection->query("SELECT * FROM tshirt ORDER BY created_at DESC");
    $tshirts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Impossible de charger les produits.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadly — Gestion des produits</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<main class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <p class="section-title mb-1">Gestion des produits</p>
                <p class="section-sub mb-0">Modifier, supprimer et piloter votre catalogue.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-success" href="admin_add_tshirt.php">Ajouter un habit</a>
                <a class="btn btn-outline-primary" href="catalogue.php">Voir le catalogue</a>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">Le produit a bien été mis à jour.</div>
        <?php endif; ?>
        <?php if ($deleted): ?>
            <div class="alert alert-success">Le produit a bien été supprimé.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($tshirts)): ?>
            <div class="alert alert-secondary">Aucun produit disponible.</div>
        <?php else: ?>
            <div class="table-responsive shadow-sm rounded bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Visuel</th>
                            <th scope="col">Nom</th>
                            <th scope="col">Prix</th>
                            <th scope="col">Statut</th>
                            <th scope="col">Ajouté le</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tshirts as $tshirt): ?>
                            <tr>
                                <td><?= htmlspecialchars($tshirt['id']) ?></td>
                                <td style="width:80px;">
                                    <?php if (!empty($tshirt['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($tshirt['image_url']) ?>" alt="<?= htmlspecialchars($tshirt['name']) ?>" class="img-fluid rounded" style="max-height:70px; object-fit:contain;">
                                    <?php else: ?>
                                        <span class="text-muted">Aucune image</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= htmlspecialchars($tshirt['name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($tshirt['color'] ?: 'Couleur unique') ?> · <?= htmlspecialchars($tshirt['size_list'] ?: 'S M L') ?></small>
                                </td>
                                <td>
                                    <?php if (!empty($tshirt['price_old'])): ?>
                                        <div class="text-muted text-decoration-line-through" style="font-size:0.9rem;"><?= number_format($tshirt['price_old'], 2, ',', ' ') ?> €</div>
                                    <?php endif; ?>
                                    <div><?= number_format($tshirt['price'], 2, ',', ' ') ?> €</div>
                                </td>
                                <td>
                                    <?php if ($tshirt['is_new']): ?><span class="badge bg-success me-1">Nouveau</span><?php endif; ?>
                                    <?php if ($tshirt['is_sale']): ?><span class="badge bg-primary">Promo</span><?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($tshirt['created_at']) ?></td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-secondary me-2" href="admin_edit_tshirt.php?id=<?= htmlspecialchars($tshirt['id']) ?>">Modifier</a>
                                    <form action="crud/del/deleteTshirt.php" method="post" class="d-inline-block" onsubmit="return confirm('Supprimer ce produit ?');">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($tshirt['id']) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<script src="./bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
</body>
</html>
