<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    if ($password === 'admin123') { // 適切なパスワードに変更
        $_SESSION['loggedin'] = true;
        header('Location: display.php');
        exit;
    } else {
        $error = "パスワードが正しくありません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="mb-4">シフト管理ログイン</h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="password" class="form-label">パスワード</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">ログイン</button>
    </form>
</div>
</body>
</html>
