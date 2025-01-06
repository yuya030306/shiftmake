<?php
function getAllShifts($pdo) {
    $query = "SELECT shifts.id, users.name AS user_name, shifts.date, shifts.start_time, shifts.end_time, roles.role_name AS role_name
              FROM shifts
              JOIN users ON shifts.user_id = users.id
              JOIN roles ON shifts.role_id = roles.id
              ORDER BY shifts.date ASC";
    return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

function getAllEmployees($pdo) {
    $query = "SELECT id, name FROM users";
    return $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
}

function isEmployeeNameExists($pdo, $name) {
    $query = "SELECT COUNT(*) FROM users WHERE name = :name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

function getShiftById($pdo, $id) {
    $query = "SELECT * FROM shifts WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
