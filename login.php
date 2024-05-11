<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFD2D2;
            margin: 0;
            padding: 0;
        }
        h2{
            color: #5151A2;
        }
        .login-container{
            background-color: #fff;
            padding: 20px 50px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }
        .switch{ 
            color: #8080C0;
            font-weight: bold;
            margin: 0 0 10px 40px;
            display: block;
        }
        .google{ 
            margin: 8px 0 0 0;
            color: #fff;
            display: block;
            font-weight: bold;
            text-decoration: none;
            background-color: #FF7575;
            padding: 7px 30px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            line-height: 40px; /* 設置與元素高度相同的行高 */
            font-size: 18px;
        }
        .google img{
            width: 35px; 
            height: 35px; 
            vertical-align: middle; /* 圖片垂直置中 */
            margin-right: 10px;  
        }
        .log_btn{
            background-color: #5151A2;
        }
        .back_btn{
            display: block;
            margin: 5px 0 20px 5px; 
            padding: 10px 20px;
            background-color: #7291b5;
            color:#fff;
            text-decoration: none;
            float: left;
            font-weight: bold;
            font-size: 22px;
        }
    </style>
</head>
<body>

<a href="display_articles.php" class="back_btn">返回文章列表</a>
<div class="container">
    <div class="login-container">
        <h2>登入</h2>
        <form action="login.php" method="POST">
            <label for="username">用戶名</label>
            <input type="text" id="username" name="username" required>
            <label for="password">密碼</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="log_btn">登入</button>
        </form>
        <br>
        <a class="switch" href="register.php">尚未有帳戶，我要註冊</a>
        
    </div>
</div>

<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db_connection.php';

    // 接收表單提交的資料
    $username = $_POST['username'];
    $password = $_POST['password'];

    // 檢查是否已經存在登入失敗次數的 session 變數，如果不存在，則設置為 0
    if (!isset($_SESSION['login_attempts']) || $_SESSION['last_username'] !== $username) {
        $_SESSION['login_attempts'] = 0;
    }

    // 檢查登入失敗次數是否已經達到 3 次
    if ($_SESSION['login_attempts'] >= 2) { // 因為從 0 開始計算，所以當次數達到 3 次時實際上是 2
        // 如果已經達到 3 次，檢查上次登入失敗的時間是否超過 3 分鐘
        if (isset($_SESSION['last_login_attempt']) && (time() - $_SESSION['last_login_attempt'] < 180)) {
            // 如果上次登入失敗時間距離現在不足 3 分鐘，則限制登入，並顯示警示視窗
            echo "<script>alert('您已經連續登入失敗超過三次，請稍後再試。'); window.location.href='login.php';</script>";
            exit;
        } else {
            // 如果上次登入失敗時間超過 3 分鐘，則重置登入失敗次數為 0
            $_SESSION['login_attempts'] = 0;
        }
    }

    // 在資料庫中查找匹配的用戶記錄
    // 準備一個 SQL 語句，使用參數化查詢
    $sql = "SELECT * FROM users WHERE name=?";
    $stmt = $conn->prepare($sql);

    // 將用戶輸入綁定到參數
    $stmt->bind_param("s", $username);

    // 執行查詢
    $stmt->execute();

    // 取得查詢結果
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        // 找到用戶，檢查密碼是否正確
        $user = $result->fetch_assoc();
        // 使用 password_verify() 函數驗證密碼
        if (password_verify($password, $user['password'])) {
            // 密碼正確，登入成功，重置登入失敗次數
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $username;
            $_SESSION['login_attempts'] = 0;
            header('Location: display_articles.php');
        } else {
            // 密碼錯誤，增加登入失敗次數
            $_SESSION['login_attempts']++;
            // 設置上次登入失敗的時間
            $_SESSION['last_login_attempt'] = time();
            // 記錄本次登入失敗的用戶名
            $_SESSION['last_username'] = $username;
            if ($_SESSION['login_attempts'] >= 3) { // 檢查是否達到三次失敗
                echo "<script>alert('您已經連續登入失敗超過三次，請稍後再試。'); window.location.href='login.php';</script>";
                exit;
            } else {
                echo "<script>alert('密碼錯誤');</script>";
            }
        }
    } else {
        // 無此用戶，仍記錄上次登入失敗的時間
        $_SESSION['last_login_attempt'] = time();
        // 記錄本次登入失敗的用戶名
        $_SESSION['last_username'] = $username;
        echo "<script>alert('無此用戶');</script>";
    }
    $conn->close();
}
?>
</body>
</html>
