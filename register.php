<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
include 'db_connection.php';
session_start();
// 接收表單提交的資料
$name = $_POST['name'];
$password = $_POST['password'];
// 將密碼加密
// $hashed_password = password_hash($password, PASSWORD_DEFAULT);
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// 將資料插入到資料庫中
$sql = "INSERT INTO users (name, password) VALUES ('$name', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    header('Location: login.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #B3D9D9;
            margin: 0;
            padding: 0;
        }
        h2{
            color: #668B8B;
        }

        #username_error,#password_error{
            font-size: 14px;
        }

        .register-container {
            background-color: #fff;
            padding: 20px 40px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-right: 20px;
        }
        .switch{ 
            color: #668B8B;
            font-weight: bold;
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
    <script>
        function checkPasswordMatch() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("confirm_password").value;

            if (password !== confirm_password) {
                document.getElementById("password_error").innerHTML = "密碼不匹配";
            } else {
                document.getElementById("password_error").innerHTML = "";
            }
        }

        function checkUsernameAvailability() {
            var username = document.getElementById("name").value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    if (response == "exist") {
                        document.getElementById("username_error").innerHTML = "用戶名已存在";
                    } else {
                        document.getElementById("username_error").innerHTML = "";
                    }
                }
            };
            xhr.open("GET", "check_username.php?username=" + username, true);
            xhr.send();
        }
    </script>
    <a href="display_articles.php" class="back_btn">返回文章列表</a>
    <br><br>
    <div class="container">
        <div class="register-container">
            <h2>註冊</h2>
            <form action="register.php" method="POST">
                <label for="name">用戶名</label>
                <input type="text" id="name" name="name" onblur="checkUsernameAvailability();" required>
                <span id="username_error" style="color: red;"></span>
                <label for="password">密碼</label>
                <input type="password" id="password" name="password" onkeyup="checkPasswordStrength();" required>
                <label for="confirm_password">確認密碼</label>
                <input type="password" id="confirm_password" name="confirm_password" onkeyup="checkPasswordMatch();" required>
                <span id="password_error" style="color: red;"></span>
                <button type="submit" class='reg_btn'>註冊</button>
            </form>
            <a class=switch href="login.php" >已有帳號，我要登入</a> 
        </div>
    </div>
</body>
</html>
