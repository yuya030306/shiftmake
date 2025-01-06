<?php
require_once '../includes/db_connect.php';

try {
    // 日付順、開始時間順にソートしてシフトを取得
    $query = "
        SELECT 
            shifts.id, 
            users.name AS user_name, 
            shifts.date, 
            shifts.start_time, 
            shifts.end_time, 
            roles.role_name AS role_name
        FROM 
            shifts
        JOIN 
            users ON shifts.user_id = users.id
        JOIN 
            roles ON shifts.role_id = roles.id
        ORDER BY 
            shifts.date ASC, 
            shifts.start_time ASC";
    $stmt = $pdo->query($query);
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}

// 削除メッセージを取得
$message = '';
if (isset($_GET['message']) && $_GET['message'] === 'shift_deleted') {
    $message = 'シフトが正常に削除されました。';
}
?>

<?php include '../includes/header.php'; ?>

<h1 class="mb-4">シフト編集</h1>

<!-- 削除成功メッセージ -->
<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<!-- 戻るボタン -->
<a href="display.php" class="btn btn-secondary mb-3">戻る</a>

<!-- シフト編集テーブル -->
<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>従業員名</th>
            <th>日付</th>
            <th>開始時間</th>
            <th>終了時間</th>
            <th>役割</th>
            <th>アクション</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($shifts as $shift): ?>
            <tr>
                <td><?= htmlspecialchars($shift['user_name']) ?></td>
                <td><?= htmlspecialchars($shift['date']) ?></td>
                <td><?= htmlspecialchars($shift['start_time']) ?></td>
                <td><?= htmlspecialchars($shift['end_time']) ?></td>
                <td><?= htmlspecialchars($shift['role_name']) ?></td>
                <td>
                    <!-- 編集ボタン -->
                    <form method="POST" action="shift_edit.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $shift['id'] ?>">
                        <button type="submit" class="btn btn-warning btn-sm">編集</button>
                    </form>
                    <!-- 削除ボタン -->
                    <form method="POST" action="shift_delete.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $shift['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
