<?php
include 'db_connection.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    //驗證並過濾用戶輸入的標題和內容 、 輸出轉義
    //FILTER_SANITIZE_STRING 會過濾掉標記和其他不安全的字符，使輸入的内容變為纯文本，防止 XSS 攻擊。
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $content = htmlspecialchars($content, ENT_QUOTES, 'UTF-8');

    //使用參數化的 SQL 查詢
    $author_id = $_SESSION['user_id'];
    $sql = "INSERT INTO articles (title, content,author_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $title, $content, $author_id);

    // 執行查詢
    if (mysqli_stmt_execute($stmt)) {
        // 成功插入文章，重定向到顯示文章列表的頁面
        header("Location: display_articles.php");
    } else {
        // 查詢執行失敗，輸出錯誤信息
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// 關閉 SQL 連接和語句對象
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>

