<?php

session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';

    // 連接資料庫，用dbname=friday.sql指定
    $dsn = "mysql:host=localhost;dbname=friday;charset=utf8mb4"; 

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $pdo = new PDO($dsn, $username, $password);
        // 設置 PDO 屬性以顯示錯誤
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "連接資料庫失敗: " . $e->getMessage();
        exit();
    }

    // 使用參數化查詢
    $user_id = $_GET['user_id']; // 假設從用戶輸入中獲取用戶ID

    $sql = "SELECT * FROM users WHERE id = :user_id";

    try {
        $stmt = $pdo->prepare($sql);
        // 綁定參數
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        // 執行查詢
        $stmt->execute();
        // 取得結果
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // 處理結果...
    } catch (PDOException $e) {
        echo "執行查詢失敗: " . $e->getMessage();
    }

    // 關閉資料庫連接
    $pdo = null;
}
?>
