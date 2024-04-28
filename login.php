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
<?php
//載入一次 'vendor/autoload.php' 這個文件
require_once 'vendor/autoload.php';

//設定取得Google API 三要素：用戶端編號、用戶端密鑰、已授權的重新導向URI
$clientID = '749899930541-ejccij3pqo93orese744os3j0j6kvk38.apps.googleusercontent.com';
$clientSecret = '';
$redirectUrl = 'http://localhost/myapp/login.php';

// 建立client端 的 request需求 給 Google
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUrl);
$client->addScope('profile');
$client->addScope('email');

$auth_url = $client->createAuthUrl();


//$_GET['code']的'code' 是取得 [授權碼]
if (isset($_GET['code'])) {
    session_start();
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    //取得GOOGLE使用者帳號資訊
    $gauth = new Google_Service_Oauth2($client);
    $google_info = $gauth->userinfo->get();
    $email = $google_info->email;
    $name = $google_info->name;
    $picture = $google_info->picture;
    
    // echo "<img src='". $picture."' >Welcome Name:" . $name . " , You are registered using email: " . $email;
    $_SESSION['email'] = $email;
    include 'db_connection.php';

    // 在資料庫中查找匹配的用戶記錄
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // 此信箱已註冊，則登入
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header('Location: display_articles.php');
    } else {
        // 無此用戶
        header('Location: registerg.php');
    }
    $conn->close();

}
?>

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
            <a class="google" href="<?php echo $client->createAuthUrl() ?>"><img src="gmail.png">Google註冊/登入</a>
            <br>
            <a class="switch" href="register.php">尚未有帳戶，我要註冊</a>
            
        </div>
    </div>
</body>
</html>


<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
include 'db_connection.php';

// 接收表單提交的資料
$username = $_POST['username'];
$password = $_POST['password'];

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

    if (password_verify($password,$user['password'])) {
        // 密碼正確，登入成功
        $_SESSION['loggedin'] = true;
        $_SESSION['user_id'] = $users['id'];
        $_SESSION['user_name'] = $username;
        header('Location: display_articles.php');
    } else {
        // 密碼錯誤
        echo "<script>alert('密碼錯誤');</script>";
    }
} else {
    // 無此用戶
    echo "<script>alert('無此用戶');</script>";
}
$conn->close();
}
?>