<?php
require_once '../../include/connect.php';

if (empty($_SESSION['user_id']) || !isAdminUser($connection)) {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../admin_add_tshirt.php');
    exit;
}

$name = trim($_POST['name'] ?? '');
$description = trim($_POST['description'] ?? '');
$color = trim($_POST['color'] ?? '');
$sizeList = trim($_POST['size_list'] ?? '');
$price = (float)($_POST['price'] ?? 0);
$priceOld = (float)($_POST['price_old'] ?? 0);
$imageUrl = '';
$isNew = isset($_POST['is_new']) ? 1 : 0;
$isSale = isset($_POST['is_sale']) ? 1 : 0;

if (!empty($_FILES['image_file']['name'])) {
    $uploadDir = __DIR__ . '/../../include/img/tshirts/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $allowedTypes = [IMAGETYPE_JPEG => 'jpg', IMAGETYPE_PNG => 'png', IMAGETYPE_GIF => 'gif'];
    $fileInfo = getimagesize($_FILES['image_file']['tmp_name']);
    $fileType = $fileInfo ? $fileInfo[2] : null;

    if (!isset($allowedTypes[$fileType])) {
        header('Location: ../../admin_add_tshirt.php?error=' . urlencode('Le fichier doit être une image JPG, PNG ou GIF.'));
        exit;
    }

    if ($_FILES['image_file']['error'] !== UPLOAD_ERR_OK) {
        header('Location: ../../admin_add_tshirt.php?error=' . urlencode('Erreur lors du téléchargement de l’image.'));
        exit;
    }

    $filename = 'tshirt_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $allowedTypes[$fileType];
    $targetPath = $uploadDir . $filename;

    if (!move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
        header('Location: ../../admin_add_tshirt.php?error=' . urlencode('Impossible d’enregistrer l’image.'));
        exit;
    }

    $imageUrl = 'include/img/tshirts/' . $filename;
}

if ($name === '' || $price <= 0) {
    header('Location: ../../admin_add_tshirt.php?error=' . urlencode('Le nom et le prix sont obligatoires.'));
    exit;
}

if ($priceOld > 0 && $priceOld < $price) {
    header('Location: ../../admin_add_tshirt.php?error=' . urlencode('Le prix ancien doit être supérieur ou égal au prix actuel.'));
    exit;
}

try {
    $sql = "INSERT INTO tshirt (name, description, color, size_list, price, price_old, is_new, is_sale, image_url, created_at) VALUES (:name, :description, :color, :size_list, :price, :price_old, :is_new, :is_sale, :image_url, NOW())";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':color', $color, PDO::PARAM_STR);
    $stmt->bindParam(':size_list', $sizeList, PDO::PARAM_STR);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':price_old', $priceOld > 0 ? $priceOld : null, PDO::PARAM_STR);
    $stmt->bindParam(':is_new', $isNew, PDO::PARAM_INT);
    $stmt->bindParam(':is_sale', $isSale, PDO::PARAM_INT);
    $stmt->bindParam(':image_url', $imageUrl, PDO::PARAM_STR);
    $stmt->execute();

    header('Location: ../../admin_add_tshirt.php?success=1');
    exit;
} catch (PDOException $e) {
    header('Location: ../../admin_add_tshirt.php?error=' . urlencode('Erreur de sauvegarde : ' . $e->getMessage()));
    exit;
}
