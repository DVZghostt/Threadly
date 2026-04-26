<?php

require_once '../../include/connect.php';

header('Content-Type: application/json');

$date = date('Y-m-d H:i:s');
$data = json_decode(file_get_contents("php://input"), true);
if ($data !== null) {
    try {
        $loginUser = trim($data["login"] ?? '');
        $password = $data["password"] ?? '';

        if ($loginUser === '' || $password === '') {
            echo json_encode(["success" => false, "message" => "Login et mot de passe requis"]);
            exit;
        }

        // Vérifier si le login existe déjà
        $sql = "SELECT id FROM user WHERE login = :loginUser";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":loginUser", $loginUser, PDO::PARAM_STR);
        $stmt->execute();
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            echo json_encode(["success" => false, "message" => "Ce login existe déjà"]);
            exit;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO user (login, mdp, crerLe) VALUES (:loginUser, :passwordHash, :date)";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":loginUser", $loginUser, PDO::PARAM_STR);
        $stmt->bindParam(":passwordHash", $passwordHash, PDO::PARAM_STR);
        $stmt->bindParam(":date", $date, PDO::PARAM_STR);
        $stmt->execute();

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['login'] = $loginUser;
        $_SESSION['user_id'] = $connection->lastInsertId();

        echo json_encode(["success" => true, "login" => $loginUser]);
    }
    catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
}

?>