<?php
require_once '../../include/connect.php';

if (empty($_SESSION['user_id']) || !isAdminUser($connection)) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../admin_tshirts.php');
    exit;
}

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($id <= 0) {
    header('Location: ../../admin_tshirts.php?error=' . urlencode('Produit introuvable.'));
    exit;
}

try {
    $stmt = $connection->prepare('SELECT image_url FROM tshirt WHERE id = :id LIMIT 1');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $tshirt = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    header('Location: ../../admin_tshirts.php?error=' . urlencode('Impossible de supprimer le produit.'));
    exit;
}

if (!$tshirt) {
    header('Location: ../../admin_tshirts.php?error=' . urlencode('Produit introuvable.'));
    exit;
}

if (!empty($tshirt['image_url']) && strpos($tshirt['image_url'], 'include/img/tshirts/') === 0) {
    $imagePath = __DIR__ . '/../../' . $tshirt['image_url'];
    if (file_exists($imagePath)) {
        @unlink($imagePath);
    }
}

try {
    $stmt = $connection->prepare('DELETE FROM tshirt WHERE id = :id');
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header('Location: ../../admin_tshirts.php?deleted=1');
    exit;
} catch (PDOException $e) {
    header('Location: ../../admin_tshirts.php?error=' . urlencode('Erreur lors de la suppression : ' . $e->getMessage()));
    exit;
}
