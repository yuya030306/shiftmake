<?php
session_start();
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    header('Location: index.php');
    exit;
}

require_once '../includes/db_connect.php';
require_once '../includes/functions.php';

$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

try {
    $query = "
        SELECT 
            users.name AS user_name, 
            shifts.start_time, 
            shifts.end_time, 
            roles.role_name AS role_name
        FROM 
            shifts
        JOIN 
            users ON shifts.user_id = users.id
        JOIN 
            roles ON shifts.role_id = roles.id
        WHERE 
            shifts.date = :selected_date
        ORDER BY 
            shifts.start_time ASC";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':selected_date', $selected_date);
    $stmt->execute();
    $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "エラー: " . $e->getMessage();
    exit;
}

$role_colors = [
    'キッチン' => 'red',
    'ホール' => 'blue',
];

// シフトをユーザーごとにグループ化
$grouped_shifts = [];
foreach ($shifts as $shift) {
    $grouped_shifts[$shift['user_name']][] = $shift;
}
?>

<?php include '../includes/header.php'; ?>

<div class="mb-3">
    <a href="shift_add.php" class="btn btn-primary">シフトを追加</a>
    <a href="shift_edit_list.php" class="btn btn-warning">シフトを編集</a>
    <a href="#addEmployeeForm" class="btn btn-success" data-bs-toggle="collapse">従業員追加</a>
    <a href="role_add_form.php" class="btn btn-info">役割追加</a>
    <a href="shift_auto_form.php" class="btn btn-warning">シフト自動生成</a>
</div>

<!-- 日付選択 -->
<form method="GET" action="display.php" class="mb-3">
    <label for="date">日付を選択:</label>
    <input type="date" id="date" name="date" value="<?= htmlspecialchars($selected_date) ?>" required>
    <button type="submit" class="btn btn-secondary">表示</button>
</form>

<!-- 日付の表示 -->
<h2 class="mb-3">表示中の日付: <?= htmlspecialchars($selected_date) ?></h2>

<!-- 色分けラベル -->
<div class="mb-3">
    <span style="background-color: red; color: white; padding: 5px; border-radius: 3px;">キッチン</span>
    <span style="background-color: blue; color: white; padding: 5px; border-radius: 3px;">ホール</span>
</div>

<!-- ガントチャート -->
<div style="overflow-x: scroll; white-space: nowrap; border: 1px solid #ddd; padding: 10px;">
    <div class="time-axis" style="display: flex; min-width: 2400px;">
        <div style="width: 150px;"></div> <!-- 名前の列 -->
        <?php for ($i = 0; $i <= 24; $i++): ?>
            <div style="flex: 1; text-align: center;"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>:00</div>
        <?php endfor; ?>
    </div>
    <div class="chart">
        <?php foreach ($grouped_shifts as $user_name => $user_shifts): ?>
            <div style="display: flex; align-items: center; margin-bottom: 10px;">
                <div style="width: 150px; text-align: right; margin-right: 10px;">
                    <?= htmlspecialchars($user_name) ?>
                </div>
                <div style="flex-grow: 1; position: relative; height: 30px; background-color: #f9f9f9; border: 1px solid #ddd; min-width: 2400px;">
                    <?php foreach ($user_shifts as $shift): ?>
                        <?php
                        $start_time = strtotime($shift['start_time']);
                        $end_time = strtotime($shift['end_time']);
                        $start_offset = (date('H', $start_time)) * (100 / 24) + (date('i', $start_time) / 60) * (100 / 24);
                        $width = (($end_time - $start_time) / 3600) * (100 / 24);
                        ?>
                        <div style="
                            position: absolute;
                            left: <?= $start_offset ?>%;
                            width: <?= $width ?>%;
                            height: 100%;
                            background-color: <?= $role_colors[$shift['role_name']] ?? 'gray' ?>;
                            border-radius: 5px;
                        "></div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
