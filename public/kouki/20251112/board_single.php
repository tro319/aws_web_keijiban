<?php
session_start();

$loginID = $_SESSION["login_id"];

if ($loginID == null) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  return;

}


$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($_GET["id"])) {

    echo "投稿が指定されていません";
    return;

}

$postID = intval($_GET["id"]);

$sql = "SELECT * FROM board_posts JOIN users ON board_posts.user_id = users.id WHERE board_posts.id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $postID]);

$postResult = $stmt->fetch();





if (!$postResult) {

    echo "投稿が見つかりません";
    return;

}

?>

<div class="link-texts">

  <a href="post.php">投稿する</a>

</div>

<hr>

<!-- 投稿情報 一件分 -->


<h2 class="sub-title">投稿詳細</h2>

<div class="inner">

<div class="article post-info" style="padding: 25px 40px;">

   <?php if (!empty($postResult)): ?>

            <?php if ($postResult["pic_name1"] != null): ?>

            <div class="images-cover post-images-cover">
    
                <ul class="images post-images">

                    <li>

                        <div class="image-radius">

                
                            <img src="/upload/image/<?= htmlspecialchars($postResult["pic_name1"]) ?>">

                
                        </div>
            
                    </li>

                </ul>

            </div>

        <?php endif; ?>


    <p>投稿内容: <?= nl2br(htmlspecialchars($postResult["content"])) ?></p>

    <div class="user_link">

      <a href="user.php?id=<?= $postResult["user_id"] ?>">

        <img src="/upload/image/<?= htmlspecialchars($postResult["img_name"]) ?>" width="80" height="60" />

        <strong style="text-dceration: none; color: #000;"><?= htmlspecialchars($postResult["name"]) ?></strong>

      </a>

  <?php endif; ?>


</div>

</div>




<div class="link-texts">

  <p><a href="board.php">◀ 掲示板に戻る</a></p>

</div>

