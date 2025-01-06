<?php
// 環境変数からデータベース接続情報を取得
$host = getenv('DB_HOST');
$port = getenv('DB_PORT');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    // データベース接続
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // エラー発生時のメッセージ出力
    echo "データベース接続エラー: " . $e->getMessage();
    echo "<br>ホスト: $host<br>ポート: $port<br>データベース: $dbname";
    exit;
}
?>
