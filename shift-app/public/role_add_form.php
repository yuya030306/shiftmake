<?php include '../includes/header.php'; ?>

<h1>役割を追加</h1>
<form method="POST" action="role_add.php" class="border p-3">
    <div class="mb-3">
        <label for="role_name" class="form-label">役割名</label>
        <input type="text" id="role_name" name="role_name" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-info">役割を追加</button>
    <a href="display.php" class="btn btn-secondary">戻る</a>
</form>

<?php include '../includes/footer.php'; ?>
