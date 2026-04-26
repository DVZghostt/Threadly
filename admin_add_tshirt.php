<?php
$page = "Ajouter un habit";
require_once 'include/connect.php';
if (empty($_SESSION['user_id']) || !isAdminUser($connection)) {
    header('Location: index.php');
    exit;
}
$success = isset($_GET['success']);
$error = trim($_GET['error'] ?? '');

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
    <title>Threadly — Ajouter un habit</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4 mb-4">Ajouter un habit</h1>
                        <?php if ($success): ?>
                            <div class="alert alert-success">Le t-shirt a bien été ajouté.</div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form action="crud/add/addTshirt.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label" for="name">Nom du t-shirt</label>
                                <input id="name" name="name" type="text" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="color">Couleur</label>
                                    <input id="color" name="color" type="text" class="form-control" list="colors-list" placeholder="Violet, Bleu...">
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
                                            <option value="<?= htmlspecialchars($sz) ?>"><?= htmlspecialchars($sz) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="image_file">Image du t-shirt</label>
                                    <input id="image_file" name="image_file" type="file" class="form-control" accept="image/*">
                                    <small class="text-muted">Choisissez une image JPG, PNG ou GIF à télécharger.</small>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-4">
                                    <label class="form-label" for="price">Prix</label>
                                    <input id="price" name="price" type="number" step="0.01" min="0" class="form-control" placeholder="24.90" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="price_old">Prix ancien</label>
                                    <input id="price_old" name="price_old" type="number" step="0.01" min="0" class="form-control" placeholder="29.90">
                                </div>
                                <div class="col-md-4"></div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-12 d-flex gap-3 align-items-center">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_new" name="is_new" value="1">
                                        <label class="form-check-label" for="is_new">Nouveau</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_sale" name="is_sale" value="1">
                                        <label class="form-check-label" for="is_sale">En promo</label>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Ajouter le t-shirt</button>
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
