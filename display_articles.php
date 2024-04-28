<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Website</title>
    <link rel="stylesheet" type="text/css" href="article.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=ZCOOL+KuaiLe&display=swap');
        body{
            background-color: #D5A4A8;
        }

        .title{
            font-size: 60px;
            color: #12263A;
        }

        h2{
            text-align: center;     
            color: #BF0060;
            font-size: 32px;
            font-family: "ZCOOL KuaiLe", sans-serif;
        }

        .article a{
        color: #068D9D; 
        text-decoration: none; 
        font-size: 32px;
        }

        .go_post{
            display: block;
            padding: 10px 25px;
            background-color: #00688B;
            color: #fff;
            text-decoration: none;
            border-radius: 25px;
            float: left;
            font-size: 28px;
            position: fixed;
            bottom: 20px; /* 距離底部的距離*/
            right: 30px; /* 距離右邊的距離*/
            z-index: 999; /* 確保它在其他內容上面 */
            font-weight:bold;
        }
        .go_login{
            display: block;
            padding: 10px 25px;
            background-color: #6D9DC5;
            color: #fff;
            text-decoration: none;
            border-radius: 20px;
            float: right;
            font-weight: bold;
            font-size: 28px;
            margin: 5px 5px 0 0; 
        }

    </style>
    
</head>
<body>
<?php
    session_start();

    // 檢查用戶是否已登入
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        // 已登入，顯示為登出
        $loginText = "登出";
        echo '<a href="post.php" class="go_post">發文 🍰</a>';
        $greeting = "🌻🌼🌻你好呀~ " . $_SESSION['user_name']."🌼🌻🌼";
        if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
            $_SESSION['loggedin'] = false; // 設置登入狀態為 false
            // 重定向到登入頁面
            header("Location: login.php");
            exit;
        } 
        // 登出按鈕連結到當前頁面，並帶上 logout 參數
        $loginLink = basename($_SERVER['PHP_SELF']) . "?logout=true";
    }
    else {
        // 未登入，顯示為登入
        $loginText = "登入";
        $loginLink = "login.php"; 
        $greeting = "登入以查看文章吧(❛◡❛✿)";
    }
    
?>
<a href="<?php echo $loginLink; ?>" class="go_login"><?php echo $loginText; ?></a> 
    <br>

    <h1 class="title">懂吃Dongchi</h1>
    <h2><?php echo $greeting; ?></h2>
    <br>
    <br>
    <?php
        include 'db_connection.php';

        $sql = "SELECT * FROM articles ORDER BY created_at DESC";
        $result = mysqli_query($conn, $sql);
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='article'>";
                echo "<h3><a href='javascript:void(0);' onclick='checkLoggedIn(" . $row["id"] . ")'>" . $row["title"] . "</a></h3>";
                echo "</div>";
            }
        } else {
            echo "找不到文章.";
        }

        
        mysqli_close($conn);
        ?>
        <script>
        function checkLoggedIn(articleId) {
            <?php if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) : ?>
                // 未登入，重定向到登入頁面
                window.location.href = 'login.php';
                var confirmLogin = confirm("請先登入！ 即可查看文章內容");
                // 如果確認要登入，則重定向到登入頁面
                if (confirmLogin) {
                    window.location.href = 'login.php';
                }
            <?php else: ?>
                // 已登入，跳轉到文章內容頁面
                window.location.href = 'article_content.php?id=' + articleId;
            <?php endif; ?>
        }
        </script>
    
</body>
</html>