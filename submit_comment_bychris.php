<?php
include 'db_connection.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 接收表單提交的資料
    $userid =  $_SESSION['user_id'];


    //11~12行 驗證並過濾用戶輸入的標題和內容 、 輸出轉義
    //FILTER_SANITIZE_STRING 會過濾掉標記和其他不安全的字符，使輸入的内容變為纯文本，防止 XSS 攻擊。
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
    $comment = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8');

    $articleid = $_SESSION['article_id'];
    //16~18行 使用參數化的 SQL 查詢，防止 SQL 注入
    $sql = "INSERT INTO comments (article_id, author_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $articleid, $userid, $comment);

    // 執行 SQL 查詢
    if ($stmt->execute()) {
         header("Location: article_content.php?id=$articleid");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // 關閉 prepared statement
    $stmt->close();

}

$conn->close();

?>