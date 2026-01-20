<?php
session_start();

// セッションからログイン中ユーザーid取得
$loginId = $_SESSION["login_id"] ?? "";

// DB接続
$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

// ログインしていなければログイン画面へ
if (empty($loginId)) {
    header("HTTP/1.1 303 See Other");
    header("Location: login.php");
    exit;
}

// 掲示板投稿取得（ユーザー情報 JOIN）
$sqlGet = "
    SELECT 
        board_posts.id,
        board_posts.user_id,
        board_posts.content,
        board_post.pic_name1,
        board_posts.created_at,
        users.name,
        users.img_name
    FROM board_posts
    INNER JOIN users ON board_posts.user_id = users.id
    ORDER BY board_posts.id DESC
";

$stmtGet = $dbh->prepare($sqlGet);
$stmtGet->execute();
$posts = $stmtGet->fetchAll();

?>
<h1>掲示板</h1>

<?php if (!empty($_SESSION["post_success"])): ?>
    <p style="color:green;"><?php echo $_SESSION["post_success"]; ?></p>
    <?php unset($_SESSION["post_success"]); ?>
<?php endif; ?>

<!-- 投稿フォームは別ページ -->
<p><a href="post.php">▶ 投稿する</a></p>

<hr>

<h2>投稿一覧</h2>

<div class="inner">
<?php foreach ($posts as $post): ?>
    <div class="content" style="border:1px solid #ccc; padding:1em; margin-bottom:1em;">

        <!-- 投稿内容 -->

        <?php if ($post["pic_name1"] != null): ?>

            <div class="images-cover post-images-cover">
    
                <ul class="images post-images">

                    <li>

                        <div class="image-radius">

                
                            <img src="/upload/image/<?= htmlspecialchars($post["pic_name1"]) ?>">

                
                        </div>
            
                    </li>

                </ul>

            </div>

        <?php endif; ?>
    
        
        <p><?= nl2br(htmlspecialchars($post["content"])) ?></p>
        <p style="font-size:0.8em; color:#666;">投稿日時: <?= $post["created_at"] ?></p>
        <div class="post_link">


          <a href="board_single.php?id=<?= $post["id"] ?>">

            <p>投稿詳細へ</p>

          </a>
          

          <hr>

          <!-- 投稿者情報（アイコン＋名前）だけ表示してリンクにする -->
          <a href="user.php?id=<?= $post['user_id'] ?>" style="text-decoration:none; color:inherit;">

            <?php if (!empty($post["img_name"])): ?>
                <img src="/upload/image/<?= htmlspecialchars($post["img_name"]) ?>"
                    style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover;">
            <?php else: ?>
                <div style="height:3em;width:3em;border-radius:50%;background:#ddd;display:inline-block;"></div>
            <?php endif; ?>

            <strong><?= htmlspecialchars($post["name"]) ?></strong>

          </a>

      </div>

    </div>  
<?php endforeach; ?>
