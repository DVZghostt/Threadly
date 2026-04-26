<?php
$page = "Catalogue";
require_once 'include/connect.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadly — Catalogue</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<main class="py-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <p class="section-title mb-1">Catalogue</p>
                <p class="section-sub mb-0">Tous les t-shirts disponibles.</p>
            </div>
            <?php if (isAdminUser($connection)) : ?>
                <a class="btn btn-success" href="admin_add_tshirt.php">Ajouter un habit</a>
            <?php endif; ?>
        </div>

        <div class="row g-4">
            <?php
                $tshirts = [];
                try {
                    $stmt = $connection->query("SELECT * FROM tshirt ORDER BY created_at DESC");
                    $tshirts = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $tshirts = [];
                }

                if (empty($tshirts)) {
                    echo '<div class="col-12"><div class="alert alert-secondary">Aucun t-shirt disponible pour le moment.</div></div>';
                } else {
                    foreach ($tshirts as $tshirt) {
                        $isNew = (bool)$tshirt['is_new'];
                        $isSale = (bool)$tshirt['is_sale'];
                        $background = $isNew ? '#EEEDFE' : ($isSale ? '#E6F1FB' : '#F1EFE8');
                        $discount = 0;
                        if ($isSale && !empty($tshirt['price_old']) && $tshirt['price_old'] > 0) {
                            $discount = round((1 - ($tshirt['price'] / $tshirt['price_old'])) * 100);
                        }
                        ?>
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="tshirt-card">
                                <div class="card-placeholder" style="background: <?= htmlspecialchars($background) ?>">
                                    <?php if ($isNew): ?>
                                        <span class="badge-new">Nouveau</span>
                                    <?php endif; ?>
                                    <?php if ($isSale && $discount > 0): ?>
                                        <span class="badge-sale">−<?= htmlspecialchars($discount) ?>%</span>
                                    <?php endif; ?>
                                    <?php if (!empty($tshirt['image_url'])): ?>
                                        <button type="button" class="btn p-0 border-0 bg-transparent product-open-modal" data-product-id="<?= htmlspecialchars($tshirt['id']) ?>" data-name="<?= htmlspecialchars($tshirt['name']) ?>" data-description="<?= htmlspecialchars($tshirt['description'] ?: 'Aucune description disponible.') ?>" data-color="<?= htmlspecialchars($tshirt['color'] ?: 'Couleur unique') ?>" data-sizes="<?= htmlspecialchars($tshirt['size_list'] ?: 'S M L') ?>" data-price="<?= number_format($tshirt['price'], 2, ',', ' ') ?>" data-price-old="<?= !empty($tshirt['price_old']) ? number_format($tshirt['price_old'], 2, ',', ' ') : '' ?>" data-image="<?= htmlspecialchars($tshirt['image_url']) ?>" data-is-new="<?= $isNew ? '1' : '0' ?>" data-is-sale="<?= $isSale ? '1' : '0' ?>" data-discount="<?= $discount ?>">
                                            <img src="<?= htmlspecialchars($tshirt['image_url']) ?>" alt="<?= htmlspecialchars($tshirt['name']) ?>" style="max-width: 90px; max-height: 100px; object-fit: contain;">
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn p-0 border-0 bg-transparent product-open-modal" data-product-id="<?= htmlspecialchars($tshirt['id']) ?>" data-name="<?= htmlspecialchars($tshirt['name']) ?>" data-description="<?= htmlspecialchars($tshirt['description'] ?: 'Aucune description disponible.') ?>" data-color="<?= htmlspecialchars($tshirt['color'] ?: 'Couleur unique') ?>" data-sizes="<?= htmlspecialchars($tshirt['size_list'] ?: 'S M L') ?>" data-price="<?= number_format($tshirt['price'], 2, ',', ' ') ?>" data-price-old="<?= !empty($tshirt['price_old']) ? number_format($tshirt['price_old'], 2, ',', ' ') : '' ?>" data-image="" data-is-new="<?= $isNew ? '1' : '0' ?>" data-is-sale="<?= $isSale ? '1' : '0' ?>" data-discount="<?= $discount ?>">
                                            <svg width="90" height="100" viewBox="0 0 90 100">
                                                <path d="M25 25 L10 45 L25 48 L25 90 L65 90 L65 48 L80 45 L65 25 Q55 18 45 20 Q35 18 25 25Z" fill="#888780" opacity="0.7"/>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <div class="p-3">
                                    <div class="card-name"><?= htmlspecialchars($tshirt['name']) ?></div>
                                    <div class="card-color-label"><?= htmlspecialchars($tshirt['color'] ?: 'Couleur unique') ?> · <?= htmlspecialchars($tshirt['size_list'] ?: 'S M L') ?></div>
                                    <p class="mb-2 text-muted" style="font-size:13px; line-height:1.4;"><?= htmlspecialchars($tshirt['description'] ?: 'Aucune description disponible.') ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            <?php if ($isSale && !empty($tshirt['price_old'])): ?>
                                                <span class="price-old"><?= number_format($tshirt['price_old'], 2, ',', ' ') ?> €</span>
                                            <?php endif; ?>
                                            <span class="price"><?= number_format($tshirt['price'], 2, ',', ' ') ?> €</span>
                                        </span>
                                        <form action="cart_action.php" method="post" class="m-0">
                                            <input type="hidden" name="action" value="add">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($tshirt['id']) ?>">
                                            <button type="submit" class="btn btn-add">+ Ajouter</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
    </div>
</main>

<?php require_once 'include/product_modal.php'; ?>
<script src="./bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
<script src="include/productModal.js"></script>
</body>
</html>
