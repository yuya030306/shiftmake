<?php
require_once '../includes/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date = $_POST['date'];
    $working_hours = explode('-', $_POST['working_hours']); // 例: 9:00-18:00
    $roles = explode(',', $_POST['roles']); // 例: キッチン:2,ホール:3
    $employees = $_POST['employees']; // 選択された従業員名の配列

    $start_time = strtotime($working_hours[0]);
    $end_time = strtotime($working_hours[1]);

    // 必要な人数チェック
    $total_required_people = 0;
    foreach ($roles as $role_entry) {
        [$role_name, $num_people] = explode(':', $role_entry);
        $total_required_people += (int)$num_people;
    }

    if (count($employees) < $total_required_people) {
        die("エラー: 必要な人数（{$total_required_people}人）に対して、選択された従業員の数が不足しています。");
    }

    // 時間スロットを生成
    $time_slots = [];
    for ($time = $start_time; $time < $end_time; $time += 3600) {
        $time_slots[] = [
            'start' => date('H:i', $time),
            'end' => date('H:i', $time + 3600)
        ];
    }

    try {
        $pdo->beginTransaction();

        $employee_index = 0;

        foreach ($time_slots as $slot) {
            foreach ($roles as $role_entry) {
                [$role_name, $num_people] = explode(':', $role_entry);
                $num_people = (int)$num_people;

                // 役割のIDを取得
                $stmt = $pdo->prepare("SELECT id FROM roles WHERE role_name = :role_name");
                $stmt->execute([':role_name' => $role_name]);
                $role_id = $stmt->fetchColumn();

                if (!$role_id) {
                    throw new Exception("役割 '$role_name' が見つかりません。");
                }

                for ($i = 0; $i < $num_people; $i++) {
                    $current_employee = $employees[$employee_index % count($employees)];
                    $employee_index++;

                    // 従業員のIDを取得
                    $stmt = $pdo->prepare("SELECT id FROM users WHERE name = :name");
                    $stmt->execute([':name' => $current_employee]);
                    $user_id = $stmt->fetchColumn();

                    if (!$user_id) {
                        throw new Exception("従業員 '$current_employee' が見つかりません。");
                    }

                    // シフトを挿入
                    $stmt = $pdo->prepare("INSERT INTO shifts (user_id, date, start_time, end_time, role_id) VALUES (:user_id, :date, :start_time, :end_time, :role_id)");
                    $stmt->execute([
                        ':user_id' => $user_id,
                        ':date' => $date,
                        ':start_time' => $slot['start'],
                        ':end_time' => $slot['end'],
                        ':role_id' => $role_id,
                    ]);
                }
            }
        }

        $pdo->commit();
        header('Location: display.php?message=shifts_generated');
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "エラー: " . $e->getMessage();
    }
} else {
    die("不正なアクセスです。");
}
?>
