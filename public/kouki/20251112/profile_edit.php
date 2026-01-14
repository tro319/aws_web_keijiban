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


if (!empty($_POST["image_base64"])) {


    $base64 = preg_replace("/^data:.+base64,/", "", $_POST["image_base64"]);

    $image_bina = base64_decode($base64);

    $image_name = strval(time()) . bin2hex(random_bytes(25)) . ".png";

    $filePath = "/var/www/upload/image/" . $image_name;    
    
    file_put_contents($filePath, $image_bina);


    $sql_update = "UPDATE users SET img_name = :img WHERE id = :id";

    $stmt_update = $dbh->prepare($sql_update);

    $stmt_update->execute([
      ":img" => basename($image_name),
      ":id" => $loginID,
    ]);

    $_SESSION["icon_name"] = basename($image_name);

		header("HTTP/1.1 302 Found");
		header("Location: profile_edit.php");
 		return;

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

  <?php if(empty($_SESSION["icon_name"]) && empty($_SESSIOIN["prof_img"])): ?>

    <p>現在未設定</p>

  <?php elseif(!empty($_SESSION["prof_img"])): ?>
    
    <img src="/upload/image/<?= htmlspecialchars(basename($_SESSION['prof_img'])) ?>"
    style="height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">

  <?php else: ?>

    <img src="/upload/image/<?= htmlspecialchars(basename($_SESSION['icon_name'])) ?>" style="height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">  

  <?php endif; ?>

</div>


<form method="POST" enctype="multipart/form-data">
  <div style="margin: 1em 0;">
    <input type="file" accept="image/*" name="image_input" id="image_input">
  </div>
  <input id="imageBase64Input" type="hidden" name="image_base64"><!-- base64を送る用のinput (非表示) -->
  <canvas id="imageCanvas" style="display: none;"></canvas><!-- 画像縮小に使うcanvas (非表示) -->

<br>

  <label> 自己紹介

    <textarea type="text" id="introd" name="introd" maxlength="1000" required></textarea>


</label>

<br>

  <button type="submit">送信</button>
</form>


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
document.addEventListener("DOMContentLoaded", () => {
  const imageInput = document.getElementById("image_input");
  imageInput.addEventListener("change", () => {
    if (imageInput.files.length < 1) {
      // 未選択の場合
      return;
    }
    const file = imageInput.files[0];
    if (!file.type.startsWith('image/')){ // 画像でなければスキップ
      return;
    }
    // 画像縮小処理
    const imageBase64Input = document.getElementById("imageBase64Input"); // base64を送るようのinput
    const canvas = document.getElementById("imageCanvas"); // 描画するcanvas
    const reader = new FileReader();
    const image = new Image();
    reader.onload = () => { // ファイルの読み込み完了したら動く処理を指定
      image.onload = () => { // 画像として読み込み完了したら動く処理を指定
        // 元の縦横比を保ったまま縮小するサイズを決めてcanvasの縦横に指定する
        const originalWidth = image.naturalWidth; // 元画像の横幅
        const originalHeight = image.naturalHeight; // 元画像の高さ
        const maxLength = 1000; // 横幅も高さも1000以下に縮小するものとする
        if (originalWidth <= maxLength && originalHeight <= maxLength) { // どちらもmaxLength以下の場合そのまま
            canvas.width = originalWidth;
            canvas.height = originalHeight;
        } else if (originalWidth > originalHeight) { // 横長画像の場合
            canvas.width = maxLength;
            canvas.height = maxLength * originalHeight / originalWidth;
        } else { // 縦長画像の場合
            canvas.width = maxLength * originalWidth / originalHeight;
            canvas.height = maxLength;
        }
        // canvasに実際に画像を描画 (canvasはdisplay:noneで隠れているためわかりにくいが...)
        const context = canvas.getContext("2d");
        context.drawImage(image, 0, 0, canvas.width, canvas.height);
        // canvasの内容をbase64に変換しinputのvalueに設定
        imageBase64Input.value = canvas.toDataURL();
      };
      image.src = reader.result;
    };
    reader.readAsDataURL(file);
  });
});

console.log(imageBase64Input.value);

</script>


