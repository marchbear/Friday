<?php
include 'db_connection.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 接收表單提交的資料
    $userid =  $_SESSION['user_id'];
    $comment = $_POST['comment'];
    $articleid = $_SESSION['article_id'];
    // 將資料插入到 comment 資料表中
    $sql = "INSERT INTO comments (article_id,author_id, content) VALUES ('$articleid', '$userid', '$comment')";

    if ($conn->query($sql) === TRUE) {
         header("Location: article_content.php?id=$articleid");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>