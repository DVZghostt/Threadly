<?php
try {
    $pdo = new PDO('mysql:host=localhost;port=3307;dbname=threadly;charset=utf8mb4','root','');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "DATABASES:\n";
    print_r($pdo->query('SHOW DATABASES')->fetchAll(PDO::FETCH_COLUMN));
    echo "\nUSER TABLE:\n";
    $row = $pdo->query('SHOW CREATE TABLE `user`')->fetch(PDO::FETCH_ASSOC);
    print_r($row);
} catch (Throwable $e) {
    echo 'ERROR: ' . $e->getMessage() . "\n";
}
