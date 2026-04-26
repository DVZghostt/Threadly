<?php

$page = "Accueil";
require_once 'include/connect.php';

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Threadly — Accueil</title>
    <link href="./bootstrap-5.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="./index.css" rel="stylesheet">
</head>
<body>

<?php require_once 'include/navbar.php'; ?>

<!-- ── HERO ── -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <span class="hero-tag">Nouvelle collection printemps 2026</span>
                <h1 class="hero-title">Des t-shirts qui<br>vous <em>ressemblent</em></h1>
                <p class="hero-sub">Matières douces, coupes modernes, style sans effort. Threadly habille votre quotidien avec élégance.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="catalogue.php" class="btn btn-hero">Découvrir la collection</a>
                    <a href="#vedettes" class="btn btn-hero-ghost">Nos meilleures ventes</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-visual">
                    <svg width="180" height="200" viewBox="0 0 180 200">
                        <path d="M50 50 L20 85 L48 90 L48 175 L132 175 L132 90 L160 85 L130 50 Q110 38 90 42 Q70 38 50 50Z" fill="#7F77DD" opacity="0.7"/>
                        <text x="90" y="120" text-anchor="middle" font-size="14" fill="#534AB7" font-family="sans-serif">Threadly</text>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ── T-SHIRTS VEDETTES ── -->
<section id="vedettes" class="py-5">
    <div class="container">
        <p class="section-title">T-shirts vedettes</p>
        <p class="section-sub">La sélection de la semaine, choisie pour vous.</p>

        <?php
            $tshirts = [];
            try {
                $stmt = $connection->query("SELECT * FROM tshirt ORDER BY created_at DESC LIMIT 4");
                $tshirts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                $tshirts = [];
            }
        ?>

        <div class="row g-4">
            <?php if (empty($tshirts)): ?>
                <div class="col-12">
                    <div class="alert alert-secondary">Aucun t-shirt disponible actuellement.</div>
                </div>
            <?php else: ?>
                <?php foreach ($tshirts as $tshirt): ?>
                    <?php
                        $isNew = (bool)$tshirt['is_new'];
                        $isSale = (bool)$tshirt['is_sale'];
                        $background = $isNew ? '#EEEDFE' : ($isSale ? '#E6F1FB' : '#F1EFE8');
                        $discount = 0;
                        if ($isSale && !empty($tshirt['price_old']) && $tshirt['price_old'] > 0) {
                            $discount = round((1 - ($tshirt['price'] / $tshirt['price_old'])) * 100);
                        }
                    ?>
                    <div class="col-6 col-md-3">
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
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ── BANNIÈRE PROMO ── -->
<section class="py-4">
    <div class="container">
        <div class="promo-banner d-flex flex-wrap justify-content-between align-items-center gap-4">
            <div>
                <h2>Livraison gratuite dès 50 €</h2>
                <p>Retours offerts sous 30 jours &nbsp;·&nbsp; Paiement sécurisé</p>
            </div>
            <a href="catalogue.php" class="btn btn-hero">Voir tout le catalogue →</a>
        </div>
    </div>
</section>

<!-- ── FOOTER ── -->
<footer class="mt-5 py-4 border-top">
    <div class="container d-flex flex-wrap justify-content-between align-items-center">
        <span class="text-muted" style="font-size:13px">© 2026 Threadly</span>
        <div class="d-flex gap-4">
            <a href="#" class="text-muted text-decoration-none" style="font-size:13px">CGV</a>
            <a href="#" class="text-muted text-decoration-none" style="font-size:13px">Confidentialité</a>
            <a href="#" class="text-muted text-decoration-none" style="font-size:13px">Contact</a>
            <a href="#" class="text-muted text-decoration-none" style="font-size:13px">FAQ</a>
        </div>
    </div>
</footer>

<?php require_once 'include/product_modal.php'; ?>
<script src="./bootstrap-5.3.6/js/bootstrap.bundle.min.js"></script>
<script src="include/productModal.js"></script>
</body>
</html>