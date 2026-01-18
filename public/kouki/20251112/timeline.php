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
        board_posts.*,
        users.name,
        users.img_name
        FROM board_posts
        JOIN users ON board_posts.user_id = users.id
        JOIN followings ON followings.follow_id = board_posts.user_id
        WHERE followings.follower_id = :login_id
        ORDER BY board_posts.id DESC
";

$stmtGet = $dbh->prepare($sqlGet);
$stmtGet->execute([":login_id" => $loginId]);
$posts = $stmtGet->fetchAll();

?>
<h1>タイムライン</h1>


<!-- 投稿フォームは別ページ -->
<p><a href="post.php">▶ 投稿する</a></p>

<hr>

<h2>投稿一覧</h2>

<div class="inner">
<?php foreach ($posts as $post): ?>
    <div class="content" style="border:1px solid #ccc; padding:1em; margin-bottom:1em;">

        <!-- 投稿内容 -->
        <p><?= nl2br(htmlspecialchars($post["board_posts.content"])) ?></p>
        <p style="font-size:0.8em; color:#666;">投稿日時: <?= $post["board_posts.created_at"] ?></p>
        <div class="post_link">


        <a href="board_single.php?id=<?= $post["board_posts.id"] ?>">

          <p>投稿詳細へ</p>

        </a>
          

        <hr>

        <!-- 投稿者情報（アイコン＋名前）だけ表示してリンクにする -->
        <a href="user.php?id=<?= $post['board_posts.user_id'] ?>" style="text-decoration:none; color:inherit;">

            <?php if (!empty($post["users.img_name"])): ?>
                <img src="/upload/image/<?= htmlspecialchars($post["users.img_name"]) ?>"
                    style="height: 3em; width: 3em; border-radius: 50%; object-fit: cover;">
            <?php else: ?>
                <div style="height:3em;width:3em;border-radius:50%;background:#ddd;display:inline-block;"></div>
            <?php endif; ?>

            <strong><?= htmlspecialchars($post["users.name"]) ?></strong>

        </a>

    </div>
<?php endforeach; ?>
</div>
