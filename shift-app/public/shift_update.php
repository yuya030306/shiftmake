<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $role_id = $_POST['role_id']; // 修正: role_id を使用

    try {
        // クエリを修正: role_id を使用
        $stmt = $pdo->prepare("UPDATE shifts SET date = :date, start_time = :start_time, end_time = :end_time, role_id = :role_id WHERE id = :id");
        $stmt->execute([
            ':id' => $id,
            ':date' => $date,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':role_id' => $role_id,
        ]);

        header('Location: display.php?message=shift_updated');
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
} else {
    die("不正なアクセスです。");
}
?>
