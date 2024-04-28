<?php
// 包含資料庫連線檔案
include 'db_connection.php';

// 檢查是否有傳遞用戶名參數
if (isset($_GET['username'])) {
    // 獲取前端發送的用戶名
    $username = $_GET['username'];

    // 在資料庫中查詢該用戶名是否已存在
    $sql = "SELECT * FROM users WHERE name='$username'";
    $result = mysqli_query($conn, $sql);

    // 如果查詢結果有資料，表示用戶名已存在，則返回 "exist" 給前端
    if (mysqli_num_rows($result) > 0) {
        echo "exist";
    } else {
        // 如果查詢結果沒有資料，表示用戶名不存在，則返回其他值給前端
        echo "not_exist";
    }
} else {
    // 如果沒有傳遞用戶名參數，則返回錯誤訊息給前端
    echo "Error: Missing username parameter";
}

// 關閉資料庫連線
mysqli_close($conn);
?>
