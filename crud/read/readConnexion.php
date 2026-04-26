<?php
require_once '../../include/connect.php';

$data = json_decode(file_get_contents("php://input"), true);
if ($data != null) {
    try {
        $login = $data["login"];
        $password = $data["password"];

        header('Content-Type: application/json');

        // 1. On cherche juste par login
        $sql = "SELECT * FROM user WHERE login = :login";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(":login", $login, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // 2. On vérifie le mot de passe
        if (!$user) {
            echo json_encode(["success" => false, "message" => "Login introuvable"]);
        } else if (!password_verify($password, $user['mdp'])) {
            echo json_encode(["success" => false, "message" => "Mot de passe incorrect"]);
        } else {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['login'] = $user['login'];
            if (isset($user['id'])) {
                $_SESSION['user_id'] = $user['id'];
            }
            echo json_encode(["success" => true, "user" => $user]);
        }
    }
    catch (PDOException $e) {
        echo json_encode(["error" => $e->getMessage()]);
    }
}
?>