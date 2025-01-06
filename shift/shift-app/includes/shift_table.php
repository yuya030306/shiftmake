<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            <th>ユーザー名</th>
            <th>日付</th>
            <th>開始時間</th>
            <th>終了時間</th>
            <th>役割</th>
            <th>アクション</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($shifts as $shift): ?>
            <tr>
                <td><?= htmlspecialchars($shift['user_name']) ?></td>
                <td><?= htmlspecialchars($shift['date']) ?></td>
                <td><?= htmlspecialchars($shift['start_time']) ?></td>
                <td><?= htmlspecialchars($shift['end_time']) ?></td>
                <!-- 修正: role_name を使用 -->
                <td><?= htmlspecialchars($shift['role_name']) ?></td>
                <td>
                    <form method="POST" action="shift_edit.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $shift['id'] ?>">
                        <button type="submit" class="btn btn-warning btn-sm">編集</button>
                    </form>
                    <form method="POST" action="shift_delete.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $shift['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">削除</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
