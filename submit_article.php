<?php
include 'db_connection.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];
    $author_id = $_SESSION['user_id'];
    $sql = "INSERT INTO articles (title, content,author_id) VALUES ('$title', '$content','$author_id')";
    if (mysqli_query($conn, $sql)) {
        header("Location: display_articles.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>