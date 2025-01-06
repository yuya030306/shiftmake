<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['id'])) {
        die("削除対象が指定されていません。");
    }

    $id = $_POST['id'];

    try {
        // シフトを削除
        $stmt = $pdo->prepare("DELETE FROM shifts WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // 削除後にシフト編集ページにリダイレクト
        header('Location: shift_edit_list.php?message=shift_deleted');
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    die("不正なアクセスです。");
}
?>
