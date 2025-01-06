<?php
require_once '../includes/db_connect.php';

try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ガントチャート形式のシフト表示</title>
    <style>
        .chart-container {
            width: 100%;
            margin: 20px auto;
            padding: 20px;
        }
        .legend {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .legend-item .color-box {
            width: 20px;
            height: 20px;
            border-radius: 3px;
        }
        .time-axis {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-bottom: 10px;
        }
        .time-axis div {
            flex: 1;
            text-align: center;
        }
        .chart {
            display: flex;
            flex-direction: column;
        }
        .row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .row .name {
            width: 150px;
            text-align: right;
            margin-right: 10px;
        }
        .row .bar-container {
            flex-grow: 1;
            height: 30px;
            position: relative;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .row .bar {
            position: absolute;
            height: 100%;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="mb-4">ガントチャート形式のシフト表示</h1>

    <a href="display.php" class="btn btn-secondary mb-3">シフト一覧に戻る</a>

    <form method="GET" action="shift_chart.php" class="mb-4">
        <label for="date">日付を選択:</label>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($selected_date) ?>" required>
        <button type="submit">表示</button>
    </form>

    <div class="legend">
        <?php foreach ($role_colors as $role => $color): ?>
            <div class="legend-item">
                <div class="color-box" style="background-color: <?= $color ?>;"></div>
                <span><?= htmlspecialchars($role) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="time-axis">
        <?php for ($i = 8; $i <= 20; $i++): ?>
            <div><?= $i ?>:00</div>
        <?php endfor; ?>
    </div>

    <div class="chart-container">
        <div class="chart">
            <?php foreach ($shifts as $shift): ?>
                <?php
                $start_time = strtotime($shift['start_time']);
                $end_time = strtotime($shift['end_time']);
                $start_offset = (date('H', $start_time) - 8) * (100 / 13) + (date('i', $start_time) / 60) * (100 / 13);
                $width = (($end_time - $start_time) / 3600) * (100 / 13);
                ?>
                <div class="row">
                    <div class="name"><?= htmlspecialchars($shift['user_name']) ?></div>
                    <div class="bar-container">
                        <div class="bar"
                             style="left: <?= $start_offset ?>%; 
                                    width: <?= $width ?>%; 
                                    background-color: <?= $role_colors[$shift['role_name']] ?? 'gray' ?>;">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
</body>
</html>
