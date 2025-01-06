<div id="registerForm" class="collapse">
    <form method="POST" action="display.php" class="border p-3">
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
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
            <label for="role" class="form-label">役割</label>
            <input type="text" id="role" name="role" class="form-control" required>
        </div>
        <button type="submit" name="register_shift" class="btn btn-success">登録</button>
    </form>
</div>
