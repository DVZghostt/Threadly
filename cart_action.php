<?php
require_once 'include/connect.php';

$action = $_POST['action'] ?? '';
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php';

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

switch ($action) {
    case 'add':
        if ($id > 0) {
            $stmt = $connection->prepare('SELECT id FROM tshirt WHERE id = :id');
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->fetchColumn()) {
                if (isset($_SESSION['cart'][$id])) {
                    $_SESSION['cart'][$id] += 1;
                } else {
                    $_SESSION['cart'][$id] = 1;
                }
            }
        }
        break;
    case 'remove':
        if ($id > 0 && isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
        break;
    case 'update':
        $qty = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
        if ($id > 0 && $qty > 0) {
            $_SESSION['cart'][$id] = $qty;
        } elseif ($id > 0) {
            unset($_SESSION['cart'][$id]);
        }
        break;
    case 'clear':
        unset($_SESSION['cart']);
        break;
}

header('Location: ' . $redirect);
exit;
