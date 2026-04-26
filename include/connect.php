<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Connection au serveur
    try {

        $dns = 'mysql:host=localhost;port=3307;dbname=Threadly'; //indication sur le SGBDR utilisé et le nom de la base de données qu'on souhaite utilisé
        $utilisateur = 'root'; //identifiant
        $motDePasse = ''; //mot de passe
        $connection = new PDO( $dns, $utilisateur, $motDePasse ); // connnexion à la base de données
        $connection->exec("USE Threadly");
        $connection->query("SET NAMES utf8"); // utilisation de l'encodage utf8 pour les accents et autres

    } catch ( Exception $e ) { // capture de l'erreur si il y en a une

        echo "Connection à MariaDB impossible : ", $e->getMessage(); // affichage de l'erreur survenue lors de l'échec à la connexion

        die(); // arrêt du code
    }

    function isAdminUser(PDO $connection) {
        if (empty($_SESSION['user_id'])) {
            return false;
        }

        try {
            $stmt = $connection->prepare("SELECT 1 FROM droit WHERE user_id = :user_id AND role = 'admin' LIMIT 1");
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            return (bool)$stmt->fetchColumn();
        } catch (Exception $e) {
            return false;
        }
    }
?>

