<?php
session_start();

// エラー有無格納変数初期化
$err = null;

// セッションからログイン中ユーザーid取得

$loginID = $_SESSION["login_id"] ?? "";


// DBに接続
$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");


// ユーザーIDがセッションに無ければログイン画面へ
if (empty($_SESSION["login_id"])) {
    header("HTTP/1.1 303 See Other");
    header("Location: ./login.php");
    exit;
}


if (!empty($_FILES["image_file"])) {

  $tmp = $_FILES["image_file"]["tmp_name"];

  $imageName = time() . bin2hex(random_bytes(25)) . ".png";

  $filePath = "/var/www/upload/" . $imageName;

  $success = move_uploaded_file($tmp, $filePath);

  if (!$success) {
    
    var_dump("move_failed", $tmp, $filePath, error_get_last());

  }

  $sql_update = "UPDATE users SET img_name = :img WHERE id = :id";

  $stmt_update = $dbh->prepare($sql_update);

  $stmt_update->execute([
    ":img" => $imageName,
    ":id" => $loginID,
  ]);

  $_SESSION["prof_img"] = $imageName;

  header("HTTP/1.1 303 See Other");
  header("Location: profile_edit.php");
  exit;

}

if (!empty($_POST["introd"])) {

  $introd = htmlspecialchars($_POST["introd"]);

  $sql_update2 = "UPDATE users SET introd = :introd WHERE id = :id";

  $stmt_update2 = $dbh->prepare($sql_update2);

  $stmt_update2->execute([
    ":introd" => $introd,
    ":id" => $loginID,
  ]);

  $_SESSION["introd"] = $introd;

}

if (!empty($loginID)) {

  $sql_get = "SELECT * FROM users WHERE id = :id";

  $stmt_get = $dbh->prepare($sql_get);

  $stmt_get->execute([
    ":id" => $loginID,
  ]);

  $userGet = $stmt_get->fetch();

  $profImg = $userGet["img_name"];

  $_SESSION["prof_img"] = $profImg;


}

?>

<h1>アイコン画像設定</h1>

<?php if (!empty($_SESSION["update_success"])): ?>
    <p style="color:green;"><?php echo $_SESSION["update_success"]; ?></p>
    <?php unset($_SESSION["update_success"]); ?>
<?php endif; ?>


<div>

  <?php if(empty($_SESSION["prof_img"])): ?>

    <p>現在未設定</p>

  <?php elseif(!empty($_SESSION["prof_img"])): ?>
    
    <img src="/upload/image/<?= htmlspecialchars($_SESSION["prof_img"]) ?>"
    style="height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">


  <?php endif; ?>

</div>


<form method="POST" enctype="multipart/form-data">
  <div style="margin: 1em 0;">

    <input type="file" accept="image/*" name="image_file" id="image_input">

  </div>

  <br>

  <label> 自己紹介

    <textarea id="introd" name="introd" maxlength="1000" required></textarea>


  </label>

  <br>

  <button type="submit">送信</button>

</form>

<canvas id="canvas" style="display: none;"></canvas>


<hr>

<h2>会員情報</h2>

<div class="inner">

  <?php if (!empty($userGet)): ?>

    <div class="content">

      <p>ユーザーネーム: <?php echo $userGet["name"]; ?></p>
      <p>メールアドレス: <?php echo $userGet["email"]; ?></p>
      <p>自己紹介: <?php echo $userGet["introd"]; ?></p>


    </div>

  <?php endif; ?>

</div>

<script>


document.querySelector("form").addEventListener("submit", async(e) => {

  e.preventDefault();

  const file = document.getElementById("image_input").files[0];

  if (!file) {

    const formData = new FormData(e.target);

    fetch("profile_edit.php", {
      
      method: "POST",
      body: formData
    }).then(() => window.location.href="profile_edit.php");
    return;

  }

  const bitmap = await createImageBitmap(file);

  
  const max = 1000;

  let w = bitmap.width;

  let h = bitmap.height;

  if (w > h && w > max) {

    h = h * ( max / w );

    w = max;

  } else if (h > max) {

    w = w * ( max / h );
    
    h = max;

  }

  const canvas = document.getElementById("canvas");

  canvas.width = w;

  canvas.height = h;  


  const ctx = canvas.getContext("2d");

  ctx.drawImage(bitmap, 0, 0, w, h);


  canvas.toBlob((blob) => {
    
    const formData = new FormData();

    formData.append("image_file", blob, "upload.png");

    formData.append("introd", document.getElementById("introd").value);


    fetch("profile_edit.php", {
    
      method: "POST",
      body: formData

    }).then(() => {
      
      window.location.href = "profile_edit.php";

    });

  }, "image/png", 0.9);

});

</script>


