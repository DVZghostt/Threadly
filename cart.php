<?php
$page = "Panier";
require_once 'include/connect.php';

$cart = $_SESSION['cart'] ?? [];
$productIds = array_keys($cart);
$tshirts = [];
$total = 0.0;

if (!empty($productIds)) {
    $placeholders = implode(',', array_fill(0, count($productIds), '?'));
    $stmt = $connection->prepare("SELECT * FROM tshirt WHERE id IN ($placeholders)");
    foreach ($productIds as $idx => $productId) {
        $stmt->bindValue($idx + 1, $productId, PDO::PARAM_INT);
    }
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $tshirt) {
        $tshirts[$tshirt['id']] = $tshirt;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadly — Panier</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<main class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h1 class="mb-4">Votre panier</h1>

                <?php if (empty($cart) || empty($tshirts)): ?>
                    <div class="alert alert-secondary">Votre panier est vide.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $productId => $quantity): ?>
                                    <?php if (empty($tshirts[$productId])) continue; ?>
                                    <?php $tshirt = $tshirts[$productId]; ?>
                                    <?php $lineTotal = $tshirt['price'] * $quantity; $total += $lineTotal; ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <?php if (!empty($tshirt['image_url'])): ?>
                                                    <img src="<?= htmlspecialchars($tshirt['image_url']) ?>" alt="<?= htmlspecialchars($tshirt['name']) ?>" width="60" height="60" style="object-fit: cover; border-radius: 12px;">
                                                <?php else: ?>
                                                    <div style="width:60px;height:60px;background:#f1efe8;border-radius:12px;"></div>
                                                <?php endif; ?>
                                                <div>
                                                    <strong><?= htmlspecialchars($tshirt['name']) ?></strong><br>
                                                    <small class="text-muted"><?= htmlspecialchars($tshirt['color'] ?: 'Couleur unique') ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= number_format($tshirt['price'], 2, ',', ' ') ?> €</td>
                                        <td>
                                            <form action="cart_action.php" method="post" class="d-flex gap-2 align-items-center">
                                                <input type="hidden" name="action" value="update">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($productId) ?>">
                                                <input type="number" name="quantity" value="<?= htmlspecialchars($quantity) ?>" min="1" class="form-control" style="width:90px;">
                                                <button type="submit" class="btn btn-sm btn-primary">Ok</button>
                                            </form>
                                        </td>
                                        <td><?= number_format($lineTotal, 2, ',', ' ') ?> €</td>
                                        <td>
                                            <form action="cart_action.php" method="post">
                                                <input type="hidden" name="action" value="remove">
                                                <input type="hidden" name="id" value="<?= htmlspecialchars($productId) ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                        <div>
                            <strong>Total :</strong> <?= number_format($total, 2, ',', ' ') ?> €
                        </div>
                        <div class="d-flex gap-2">
                            <form action="cart_action.php" method="post">
                                <input type="hidden" name="action" value="clear">
                                <button type="submit" class="btn btn-outline-secondary">Vider le panier</button>
                            </form>
                            <button class="btn btn-primary">Valider la commande</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script src="./bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
</body>
</html>
