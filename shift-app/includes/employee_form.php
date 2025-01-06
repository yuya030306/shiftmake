<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_employee'])) {
    $employee_name = $_POST['employee_name'];

    if (isEmployeeNameExists($pdo, $employee_name)) {
        header('Location: display.php?error=duplicate_employee');
        exit;
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (name) VALUES (:name)");
        $stmt->bindParam(':name', $employee_name);
        $stmt->execute();
        header('Location: display.php');
        exit;
    }
}
?>

<div id="addEmployeeForm" class="collapse">
    <form method="POST" action="display.php" class="border p-3">
        <div class="mb-3">
            <label for="employee_name" class="form-label">従業員名</label>
            <input type="text" id="employee_name" name="employee_name" class="form-control" required>
        </div>
        <button type="submit" name="add_employee" class="btn btn-success">登録</button>
    </form>
</div>
