<?php
require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

// POST リクエスト処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $date = $_POST['date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $role_id = $_POST['role_id']; // 修正: role_id を使用

    try {
        $stmt = $pdo->prepare("INSERT INTO shifts (user_id, date, start_time, end_time, role_id) VALUES (:user_id, :date, :start_time, :end_time, :role_id)");
        $stmt->execute([
            ':user_id' => $user_id,
            ':date' => $date,
            ':start_time' => $start_time,
            ':end_time' => $end_time,
            ':role_id' => $role_id,
        ]);

        header('Location: display.php?message=shift_added');
        exit;
    } catch (PDOException $e) {
        echo "エラー: " . $e->getMessage();
    }
}

// 役割データを取得
$roles = $pdo->query("SELECT id, role_name FROM roles")->fetchAll(PDO::FETCH_ASSOC);
$employees = getAllEmployees($pdo);
?>

<?php include '../includes/header.php'; ?>

<h1>シフト追加</h1>
<form method="POST" action="">
    <div class="mb-3">
        <label for="user_id" class="form-label">従業員</label>
        <select id="user_id" name="user_id" class="form-control" required>
            <option value="">選択してください</option>
            <?php foreach ($employees as $employee): ?>
                <option value="<?= htmlspecialchars($employee['id']) ?>"><?= htmlspecialchars($employee['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">日付</label>
        <input type="date" id="date" name="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="start_time" class="form-label">開始時間</label>
        <input type="time" id="start_time" name="start_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="end_time" class="form-label">終了時間</label>
        <input type="time" id="end_time" name="end_time" class="form-control" required>
    </div>
    <div class="mb-3">
        <label for="role_id" class="form-label">役割</label>
        <select id="role_id" name="role_id" class="form-control" required>
            <option value="">選択してください</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= htmlspecialchars($role['id']) ?>"><?= htmlspecialchars($role['role_name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-success">登録</button>
    <a href="display.php" class="btn btn-secondary">戻る</a>
</form>

<?php include '../includes/footer.php'; ?>
