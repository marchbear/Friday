<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
include 'db_connection.php';
session_start();
// 接收表單提交的資料
$name = $_POST['name'];
if(isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
}
// 將資料插入到資料庫中
$sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";

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
        function checkUsernameAvailability() {
            var username = document.getElementById("name").value;
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    if (response == "exist") {
                        document.getElementById("username_error").innerHTML = "用戶名已存在";
                        document.getElementById("reg_btn").disabled = true; //設置按鈕無法點擊
                        reg_btn.style.backgroundColor = "#ccc"; /// 設置按鈕為灰色

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
            <form action="registerg.php" method="POST">
                <label for="name">用戶名</label>
                <input type="text" id="name" name="name" onblur="checkUsernameAvailability();" required>
                <span id="username_error" style="color: red;"></span>
                <button type="submit" class='reg_btn'>註冊</button>
            </form>
        </div>
    </div>
</body>
</html>
