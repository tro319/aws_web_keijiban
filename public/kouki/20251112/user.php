<?php
session_start();

$loginID = $_SESSION["login_id"];


if ($loginID == null) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");

}

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($_GET["id"])) {

    echo "ユーザーが指定されていません";
    exit;

}

$userID = intval($_GET["id"]);

$sql = "SELECT * FROM users WHERE id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $userID]);

$userResult = $stmt->fetch();

$isFollowing = false;

if (!empty($loginID) && $loginID != $userID) {

  $sql = "SELECT 1 FROM followings WHERE follower_id = :my_id AND follow_id = :target_id";

  $stmt = $dbh->prepare($sql);

  $stmt->execute([
    ":my_id" => $loginID,
    ":target_id" => $userID
  ]);

  $isFollowing = $stmt->fetch() ? true : false;


}


$sql = "SELECT * FROM board_posts JOIN users ON board_posts.user_id = users.id WHERE user_id = :id";

$stmt = $dbh->prepare($sql);

$stmt->execute([":id" => $userID]);

$userPosts = $stmt->fetchAll();


if (!$userResult) {

    echo "ユーザーが存在しません";
    exit;

}


require("./read.php");
require("./header.php");
?>


<div class="container">

<!-- ユーザー情報 -->


<h1 class="page-title">ユーザー情報</h1>

<div class="user-info" style="padding: 25px 40px;">

    <?php if (!empty($userResult["img_name"])): ?>

        <img src="/upload/image/<?= htmlspecialchars($userResult["img_name"]) ?>" width="80" height="65" />

    <?php endif; ?>

    <p>ユーザーネーム: <?= htmlspecialchars($userResult["name"]) ?></p>

    <?php if (!empty($userResult["introd"])): ?>
    
        <p>自己紹介: <?= nl2br(htmlspecialchars($userResult["introd"])) ?></p>

    <?php endif; ?>


</div>


<?php if ($loginID != $userID): ?>


  <div class="follow-btn-cover">

    <button id="follow_btn" data-following="<?= $isFollowing ? '1' : '0' ?>" data-target="<?= $userID ?>"><?= $isFollowing ? "フォローをやめる" : "フォローする" ?></button>

  </div>


 <?php elseif ($loginID == $userID): ?>


  <div class="edit-btn">

    <a href="prof_update.php">プロフィール編集</a>

  </div>

<?php endif; ?>




<!-- ユーザーの投稿一覧 -->

<h2 class="sub-title">ユーザーの投稿一覧</h2>

<div class="post-infos">

<?php if (!empty($userPosts)): ?>

  <?php foreach ($userPosts as $post): ?>

    <div class="post-info">

                    <div class="images-cover post-images-cover">
    
                <ul class="images post-images">

             <?php if (isset($post["pic_name1"])): ?>



                    <li>

                        <div class="image-box">

                
                            <img src="/upload/image/<?= htmlspecialchars($post["pic_name1"]) ?>">

                
                        </div>
            
                    </li>



        <?php endif; ?>


        
             <?php if (isset($post["pic_name2"])): ?>



                    <li>

                        <div class="image-box">

                
                            <img src="/upload/image/<?= htmlspecialchars($post["pic_name2"]) ?>">

                
                        </div>
            
                    </li>



        <?php endif; ?>


                     <?php if (isset($post["pic_name3"])): ?>



                    <li>

                        <div class="image-box">

                
                            <img src="/upload/image/<?= htmlspecialchars($post["pic_name3"]) ?>">

                
                        </div>
            
                    </li>



        <?php endif; ?>

                </ul>

                    </div>

      <p>投稿文: <?= nl2br(htmlspecialchars($post["content"])) ?></p>

      <p class="post-time" style="font-size: 0.8rem; color: #666;">投稿日時: <?= $post["created_at"] ?></p>

      <div class="post-link">
  
        <a href="board_single.php?id=<?= $post["id"] ?>">投稿詳細へ</a>

      </div>       

    </div>


  <?php endforeach; ?>

<?php else: ?>

  <div class="post-info" style="padding: 25px 40px; margin: 30px auto;">

    <p>投稿がありません</p>

  </div>  

<?php endif; ?>

</div>

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

   
<?php
require("./end.php");

?> 


