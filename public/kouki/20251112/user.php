<?php
session_start();

$loginId = $_SESSION["login_id"] ?? "";

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($_GET["id"])) {

    echo "ユーザーが指定されていません";
    exit;

}

$userId = intval($_GET["id"]);

$sql = "SELECT * FROM users WHERE id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $userId]);

$userResult = $stmt->fetch();

$isFollowing = false;

if (!empty($loginId) && $loginId != $userId) {

  $sql = "SELECT 1 FROM followings WHERE follower_id = :my_id AND follow_id = :target_id";

  $stmt = $dbh->prepare($sql);

  $stmt->execute([
    ":my_id" => $loginId,
    ":target_id" => $userId
  ]);

  $isFollowing = $stmt->fetch() ? true : false;


}


$sql = "SELECT * FROM board_posts JOIN users ON board_posts.user_id = users.id WHERE user_id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $userId]);

$userPosts = $stmt->fetchAll();


if (!$userResult) {

    echo "ユーザーが存在しません";
    exit;

}

?>

<!-- ユーザー情報 -->


<h2 class="page_title">ユーザー情報</h2>

<div class="article user_info" style="padding: 25px 40px;">

    <?php if (!empty($userResult["img_name"])): ?>

        <img src="/upload/image/<?= htmlspecialchars($userResult["img_name"]) ?>" width="80" height="65" />

    <?php endif; ?>

    <p>ユーザーネーム: <?= htmlspecialchars($userResult["name"]) ?></p>

    <?php if (!empty($userResult["introd"])): ?>
    
        <p>自己紹介: <?= nl2br(htmlspecialchars($userResult["introd"])) ?></p>

    <?php endif; ?>


</div>


<?php if (!empty($loginId) && $loginId != $userId): ?>


  <div style="follow_btn_cover">

    <button id="follow_btn" data-following="<?= $isFollowing ? '1' : '0' ?>" data-target="<?= $userId ?>"><?= $isFollowing ? "フォローをやめる" : "フォローする" ?></button>

  </div>

<?php endif; ?>



<!-- ユーザーの投稿一覧 -->

<h2 class="page_titile">ユーザーの投稿一覧</h2>

<?php if (!empty($userPosts)): ?>

  <?php foreach ($userPosts as $post): ?>

    <div class="article board_info" style="padding: 25px 40px; marign: 30px auto;">

      <p>投稿文: <?= nl2br(htmlspecialchars($post["content"])) ?></p>

      <p class="board_datetime" style="font-size: 0.8rem; color: #666;">投稿日時: <?= $post["created_at"] ?></p>
       
      <hr>



    </div>

  

  <?php endforeach; ?>

<?php else: ?>

  <div class="article board_info" style="padding: 25px 40px; margin: 30px auto;">

    <p>投稿がありません</p>

  </div>  

<?php endif; ?>

<div class="back_link">

  <p><a href="board.php">◀ 掲示板に戻る</a></p>

</div>

<!-- JavaScript 非同期処理 -->

<script>

  document.addEventListener("DOMContentLoaded", () => {
    
    const btn = document.getElementById("follow_btn");

    // フォローボタンが存在しなければ、処理終了

    if (!btn) return;

    // ボタンが押された時の処理

    btn.addEventListener("click", () => {

      const isFollowing = btn.dataset.following == 1;

      const targetId = btn.dataset.target;

      
      const url = isFollowing ? "unfollow_api.php" : "follow_api.php";

      
      fetch(url, {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "target_id=" + encodeURIComponent(targetId)
      })
      .then(res => res.text())
      .then(text => {
        if (text == "OK") {

          if (isFollowing) {

            btn.textContent = "フォローする";
            btn.dataset.following = "0";
          
          } else {
  
            btn.textContent = "フォロー解除";
            btn.dataset.following = "1";

          }

        }

      });
    
    });

  });

</script>

    
