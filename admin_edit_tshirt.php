<?php
$page = "Modifier un habit";
require_once 'include/connect.php';

if (empty($_SESSION['user_id']) || !isAdminUser($connection)) {
    header('Location: index.php');
    exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: admin_tshirts.php');
    exit;
}

$error = trim($_GET['error'] ?? '');
$success = isset($_GET['success']);

$tshirt = null;
try {
    $stmt = $connection->prepare('SELECT * FROM tshirt WHERE id = :id LIMIT 1');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $tshirt = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $error = 'Impossible de récupérer le produit.';
}

if (!$tshirt) {
    header('Location: admin_tshirts.php');
    exit;
}

$colors = [];
$sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
try {
    $stmt = $connection->query("SELECT DISTINCT color FROM tshirt WHERE color IS NOT NULL AND color != '' ORDER BY color");
    $colors = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (Exception $e) {
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadly — Modifier un habit</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                            <div>
                                <h1 class="h4 mb-1">Modifier le produit</h1>
                                <p class="text-muted mb-0">ID <?= htmlspecialchars($tshirt['id']) ?> - <?= htmlspecialchars($tshirt['name']) ?></p>
                            </div>
                            <div class="d-flex gap-2">
                                <a class="btn btn-outline-secondary" href="admin_tshirts.php">Retour à la liste</a>
                            </div>
                        </div>

                        <?php if ($success): ?>
                            <div class="alert alert-success">Le produit a été mis à jour.</div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <form action="crud/edit/editTshirt.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="id" value="<?= htmlspecialchars($tshirt['id']) ?>">

                            <div class="mb-3">
                                <label class="form-label" for="name">Nom du t-shirt</label>
                                <input id="name" name="name" type="text" class="form-control" value="<?= htmlspecialchars($tshirt['name']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="4"><?= htmlspecialchars($tshirt['description']) ?></textarea>
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="color">Couleur</label>
                                    <input id="color" name="color" type="text" class="form-control" list="colors-list" value="<?= htmlspecialchars($tshirt['color']) ?>">
                                    <datalist id="colors-list">
                                        <?php foreach ($colors as $col): ?>
                                            <option value="<?= htmlspecialchars($col) ?>"></option>
                                        <?php endforeach; ?>
                                    </datalist>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="size_list">Tailles</label>
                                    <select id="size_list" name="size_list" class="form-select">
                                        <option value="">-- Sélectionner une taille --</option>
                                        <?php foreach ($sizes as $sz): ?>
                                            <option value="<?= htmlspecialchars($sz) ?>" <?= $tshirt['size_list'] === $sz ? 'selected' : '' ?>><?= htmlspecialchars($sz) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="price">Prix</label>
                                    <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" value="<?= (float)($tshirt['price'] ?? 0) ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="price_old">Prix ancien</label>
                                    <input id="price_old" name="price_old" type="number" step="0.01" min="0" class="form-control" value="<?= (float)($tshirt['price_old'] ?? '') ?>">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="image_file">Charger une nouvelle image</label>
                                    <input id="image_file" name="image_file" type="file" class="form-control" accept="image/*">
                                    <small class="text-muted">Laissez vide pour conserver l'image actuelle.</small>
                                </div>
                                <div class="col-md-4"></div>
                            </div>

                            <?php if (!empty($tshirt['image_url'])): ?>
                                <div class="mt-4">
                                    <p class="mb-2">Image actuelle :</p>
                                    <img src="<?= htmlspecialchars($tshirt['image_url']) ?>" alt="<?= htmlspecialchars($tshirt['name']) ?>" class="img-fluid rounded" style="max-height:220px; object-fit:contain;">
                                </div>
                            <?php endif; ?>

                            <div class="row g-3 mt-4">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1" <?= $tshirt['is_new'] ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="is_new">Nouveau</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_sale" name="is_sale" value="1" <?= $tshirt['is_sale'] ? 'checked' : '' ?> >
                                        <label class="form-check-label" for="is_sale">En promo</label>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="./bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
</body>
</html>
