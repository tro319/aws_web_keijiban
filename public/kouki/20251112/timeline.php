<?php
session_start();

$loginID = $_SESSION["login_id"] ?? "";

if ($loginID == null) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");

} 

// DB接続
$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

// 掲示板投稿取得（ユーザー情報 JOIN）
$sql = "
    SELECT 
        board_posts.*,
        users.name,
        users.img_name
        FROM board_posts
        JOIN users ON board_posts.user_id = users.id
        JOIN followings ON followings.follow_id = board_posts.user_id
        WHERE followings.follower_id = :login_id
        ORDER BY board_posts.id DESC
";

$stmt = $dbh->prepare($sql);
$stmt->execute([":login_id" => $loginID]);
$posts = $stmt->fetchAll();


require("./read.php");
require("./header.php");
?>


<div class="container">


<h1 class="page-title">タイムライン</h1>

<div class="posts-info">

<?php foreach ($posts as $post): ?>

    <div class="post-info" style="border:1px solid #ccc; padding:1em; margin-bottom:1em;">

        <!-- 投稿内容 -->


        <?php if ($post["pic_name1"] != null): ?>

            <div class="images-cover post-images-cover">
    
                <ul class="images post-images">

                    <li>

                        <div class="image-box">

                
                            <img src="/upload/image/<?= htmlspecialchars($post["pic_name1"]) ?>">

                
                        </div>
            
                    </li>

                </ul>

            </div>

        <?php endif; ?>

        
        <p>投稿文: <?= nl2br(htmlspecialchars($post["content"])) ?></p>

        <p class="post-time">投稿日時: <?= $post["created_at"] ?></p>

        <div class="post-link">


          <a href="board_single.php?id=<?= $post["id"] ?>">

            投稿詳細へ

          </a> 
  
        </div>  
          

          <hr>

        <div class="user-link">

          <!-- 投稿者情報（アイコン＋名前）だけ表示してリンクにする -->

          <a href="user.php?id=<?= $post['user_id'] ?>" style="text-decoration:none; color:inherit;">

            <?php if (!empty($post["img_name"])): ?>

                <img src="/upload/image/<?= htmlspecialchars($post["img_name"]) ?>"
                    style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover;">

            <?php else: ?>

                <img src="/upload/image/dummy.png" >

            <?php endif; ?>


            <span><?= htmlspecialchars($post["name"]) ?></span>

          </a>


         </div>
    
    </div>

    </hr>
   
<?php endforeach; ?>

</div>


<?php
require("./end.php");

?>


