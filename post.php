<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Website</title>
    <link rel="stylesheet" type="text/css" href="article.css">
    <style>
        body{
            background-color: #D5A4A8;
        }
        h1{
            color: #4B4237;
            font-size: 48px;
        }
        .back_btn{
            display: block;
            margin: 5px 0 20px 5px; 
            padding: 10px 20px;
            background-color: #9a4d4d;
            color: #fff;
            text-decoration: none;
            float: left;
            font-size: 24px;
            border-radius:10px;
        }
    </style>
</head>

<body>
    <?php
        session_start();
        if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
            // 未登入，重定向到登入頁面
            header("Location: login.php");
            exit;
        }
    ?>
    <a href="display_articles.php" class="back_btn">返回文章列表</a>
    <br><br>
    <h1>發表文章</h1>

    <!-- Article Form -->
    <div class="form-container">
        <form action="submit_article.php" method="POST">
            <label for="title">輸入文章標題(美食名稱、店名等)</label><br>
            <input type="text" id="title" name="title" maxlength="30"><br>
            <br>
            <label for="content">輸入內容(告訴大家他有多好吃!)</label><br>
            <textarea id="content" name="content" maxlength="300"></textarea><br>
            <input type="submit" value="Share with everyone" >
        </form>
    </div>
</body>
</html>