<?php
require_once '../includes/db_connect.php';

// 従業員データを取得
$employees = $pdo->query("SELECT name FROM users")->fetchAll(PDO::FETCH_COLUMN);
?>

<?php include '../includes/header.php'; ?>

<h1>シフト自動生成</h1>
<form method="POST" action="shift_auto_generate.php">
    <div class="mb-3">
        <label for="date" class="form-label">日付</label>
        <input type="date" id="date" name="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="working_hours" class="form-label">働く時間帯</label>
        <input type="text" id="working_hours" name="working_hours" class="form-control" placeholder="例: 9:00-16:00" required>
    </div>
    <div class="mb-3">
        <label for="roles" class="form-label">役割と人数（例: キッチン:2,ホール:3）</label>
        <input type="text" id="roles" name="roles" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="employees" class="form-label">働く人（Ctrlキーを使うと複数選択できます）</label>
        <select id="employees" name="employees[]" class="form-control" multiple required>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= htmlspecialchars($employee) ?>"><?= htmlspecialchars($employee) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">シフトを生成</button>
    <a href="display.php" class="btn btn-secondary">戻る</a>
</form>

<?php include '../includes/footer.php'; ?>
