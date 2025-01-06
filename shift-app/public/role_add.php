<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role_name = $_POST['role_name'];

    try {
        $stmt = $pdo->prepare("INSERT INTO roles (role_name) VALUES (:role_name)");
        $stmt->bindParam(':role_name', $role_name);
        $stmt->execute();

        header('Location: display.php?message=role_added');
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    die("不正なアクセスです。");
}
?>
