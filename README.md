# Friday
配置步驟
1. 下載XAMPP安裝包
2. 下載此zip
3. 將檔案都放到xampp/htdocs/myapp(自己新增一個資料夾)
4. 開啟XAMPP
5. Start Apache  & Mysql
6. 打開mysql的admin
7. 新建 friday 資料庫
8. 匯入friday.sql檔案
9. 打開網址 http://localhost/myapp/register.php

*  CSS檔案不要更改!!! 
*  Google註冊登入不能試 因為上傳GitHub會有Oauth安全問題所以我先拿掉了!!! 
## myapp資料夾-Code們，放在htdocs資料夾
*  db_connection.php 連接資料庫 
### 登入註冊
*  login.php 登入
*  register.php 註冊
*  registerg.php Google註冊/登入
*  check_username.php 註冊時，確認用戶名是否已被註冊
### 文章發表
* post.php 發文介面(填寫文章內容)
* submit_article.php 執行發文動作
### 文章瀏覽/留言
* article_content.php 文章內文
* display_articles.php 文章列表
* submit_comment.php 執行留言動作
## friday.sql
僅給資料庫架構，資料自行添加
