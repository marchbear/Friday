<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Detail</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100..900&display=swap');
    body {
        font-family: Arial, sans-serif;
        background-color: #A6808C;
        margin: 0;
        padding: 0;
    }

    .article-title {
        font-size: 36px;
        color: #407eaa;
        margin-bottom: 10px;
    }

    .article-detail {
        max-width: 1100px;
        margin: 10px auto;
        padding:  10px 40px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-size: 28px;
        font-family: "Noto Sans TC", sans-serif;
        font-weight: 500;
        color: #223530;
        line-height: 1.75;
    }

    .comment-section {
        max-width: 1100px;
        margin: 20px auto;
        padding: 40px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        font-family: "Noto Sans TC", sans-serif;
        font-weight: 500;
    }

    .comment-section h2{
        font-size: 32px;
    }

    .comment {
    background-color: #f9f9f9;
    border-radius: 5px;
    padding: 10px;
    margin-bottom: 10px;
    }

    .comment:nth-child(odd) {
    border: 3px solid #CF938F; /* 奇數行的邊框顏色 */
    border-radius: 5px;
    }

    .comment:nth-child(even) {
        border: 3px solid #7FB069; /* 偶數行的邊框顏色 */
        border-radius: 5px;
    }

    .comment p {
        margin: 0;
    }
    
    .say{
        font-size:24px;
    }

    .time{
        text-align: right;
        font-size:18px;
    }

    .back-btn {
        background-color: #36648B;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 26px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .back-btn:hover {
        background-color: #0056b3;
    }

    .comment-btn{
        font-size: 22px;
        display: block;
        padding: 10px 20px;
        margin: 10px auto 30px;
        background-color: #695F6E;
    }
    </style>
</head>
<body>
    <div class="article-detail">
        <?php
        session_start();
        include 'db_connection.php';

        // 檢查是否提供了文章 ID
        if (isset($_GET['id'])) {
            if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
                // 未登入，重定向到登入頁面
                header("Location: login.php");
                exit;
            }
            $article_id = $_GET['id'];

            // 根據文章 ID 查詢該文章的詳細內容
            $sql = "SELECT articles.*, users.name AS author FROM articles 
            INNER JOIN users ON articles.author_id = users.id
            WHERE articles.id = $article_id";
            $result = mysqli_query($conn, $sql);

            if (mysqli_num_rows($result) == 1) {
                $_SESSION['article_id']=$article_id;
                $row = mysqli_fetch_assoc($result);
                echo "<div class='article-title'>";
                echo "<a href='display_articles.php' class='back-btn'>&#8592;文章列表</a>";
                echo "<h3>" . $row["title"] . "</h3>";
                echo "</div>";
                echo "<p>Author：" . $row["author"] . "</p>";
                echo "<p>" . $row["content"] . "</p>";
                echo "<p class='time'>Created at: " . $row["created_at"] . "</p>";
                echo "</div>";
            } else {
                echo "Article not found.";
            }
        } else {
            echo "Invalid request.";
        }

        mysqli_close($conn);
        ?>
    </div>

    <div class="comment-section">
    <form action="submit_comment.php" method="POST">
        <label for="comment">我想說.....</label>
        <textarea id="comment" name="comment" rows="4" maxlength="100" required></textarea>
        <button type="submit" class=comment-btn>提交</button>
    </form>

    <!-- 留言列表 -->
    <div class="comment-list">
        <h2>留言區</h2>
        <!-- 這裡顯示從資料庫中獲取的留言 -->
        <?php
        // 連接資料庫
        include 'db_connection.php';

        // 查詢留言
        $sql = "SELECT c.*, users.name AS author, a.title AS article_title 
        FROM comments AS c 
        INNER JOIN users ON c.author_id = users.id
        INNER JOIN articles AS a ON c.article_id = a.id
           WHERE c.article_id = ?
        ORDER BY c.created_at DESC";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $article_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
        }

        // 如果有留言，顯示它們
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='comment'>";
                echo "<p class ='say'>" . $row["author"] . ": " . $row["content"] . "</p>";
                echo "<p class='time'>" . date('m/d H:i', strtotime($row["created_at"])) . "</p>";
                echo "</div>";
            }
        } else {
            echo "還沒有人留言呢 ヽ༼⊙_⊙༽ﾉ";
        }

        // 關閉資料庫連接
        mysqli_close($conn);
        ?>
    </div>
</div>
</body>
</html>
