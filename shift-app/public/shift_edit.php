<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $shift = getShiftById($pdo, $id);

    if (!$shift) {
        die("シフトが見つかりません。");
    }

    // 役割データを取得
    $roles = $pdo->query("SELECT id, role_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);
} else {
    die("不正なアクセスです。");
}
?>

<?php include '../includes/header.php'; ?>

<h1>シフト編集</h1>
<form method="POST" action="shift_update.php">
    <input type="hidden" name="id" value="<?= htmlspecialchars($shift['id']) ?>">
    <div class="mb-3">
        <label>日付: <input type="date" name="date" value="<?= htmlspecialchars($shift['date']) ?>" required></label>
    </div>
    <div class="mb-3">
        <label>開始時間: <input type="time" name="start_time" value="<?= htmlspecialchars($shift['start_time']) ?>" required></label>
    </div>
    <div class="mb-3">
        <label>終了時間: <input type="time" name="end_time" value="<?= htmlspecialchars($shift['end_time']) ?>" required></label>
    </div>
    <div class="mb-3">
        <label>役割:
            <select name="role_id" class="form-control" required>
                <?php foreach ($roles as $role): ?>
                    <option value="<?= htmlspecialchars($role['id']) ?>" <?= $role['id'] == $shift['role_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($role['role_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </div>
    <button type="submit" class="btn btn-primary">更新</button>
    <a href="display.php" class="btn btn-secondary">戻る</a>
</form>

<?php include '../includes/footer.php'; ?>
