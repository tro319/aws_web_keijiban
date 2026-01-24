<?php
session_start();

// DBに接続

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");


if (!empty($_POST["user_name"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {


  // ユーザーネーム重複チェック

  $sqlName = "SELECT name FROM users WHERE name = :user_name";

  $stmtName = $dbh->prepare($sqlName);

  $stmtName->execute([

      ":user_name" => $_POST["user_name"],

  ]); 


  $nameCheck = $stmtName->fetch();

  if ($nameCheck != null) {

    header("HTTP/1.1 303 See Other");
    header("Location: ./user_entry.php?err=name");  
    return;

  }

  // メールアドレス重複チェック

  $sqlEmail = "SELECT email FROM users WHERE email = :email";

  $stmtEmail = $dbh->prepare($sqlEmail);

  $stmtEmail->execute([

      ":email" => $_POST["email"],

  ]);

  $emailCheck = $stmtEmail->fetch();


  if ($emailCheck != null) {

    header("HTTP/1.1 303 See Other");
    header("Location: ./user_entry.php?err=email");
    return;


  }

  // パスワードハッシュ化

  $hashPass = password_hash($_POST["password"], PASSWORD_DEFAULT);

  // 画像取得

  $imageName = null;

  if ($_FILES["image_file"]["error"] == 0) {

    $tmp = $_FILES["image_file"]["tmp_name"];

    $imageName = time() . bin2hex(random_bytes(25)) . ".png";

    $filePath = "/var/www/public/upload/image/" . $imageName;

    $success = move_uploaded_file($tmp, $filePath);

    if (!$success) {

      var_dump("move_failed", $tmp, $filePath, error_get_last());

    }

    $_SESSION["prof_img"] = $imageName;

  }

  // 自己紹介文取得

  $introd = null;

  if (!empty($_POST["introd"])) {

    $introd = htmlspecialchars($_POST["introd"]);

  }





  // sql

  $sql = "INSERT INTO users (name, email, password, introd, img_name) VALUES(:user_name, :email, :password, :introd, :img)";

  // INSERTする

  $stmt = $dbh->prepare($sql);

  $stmt->execute([
      ":user_name" => $_POST["user_name"],
      ":email" => $_POST["email"],
      ":password" => $hashPass,
      ":introd" => $introd,
      ":img" => $imageName,
  ]);


  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  return;




}


require("./read.php");
?>

<div class="container">

<h1 class="page-title">ユーザー登録フォーム</h1>

<?php if (!empty($_GET["err"]) && $_GET["err"] == "name"): ?>

<p style="color: #F00;">入力されたユーザーネームはすでに登録されています。</p>

<?php endif; ?>


<?php if (!empty($_GET["err"]) && $_GET["err"] == "email"): ?>

<p style="color: #F00;">入力されたメールアドレスはすでに登録されています。</p>

<?php endif; ?>


<form method="post" enctype="multipart/form-data" class="form signup-form">

<label>

<span>名前</span>

<input type="text" name="user_name" maxlength="50"  required />


</label>



<br>

<label>

<span>メールアドレス</span>

<input type="email" name="email" maxlength="256" required />

</label>


<br>


<label>

<span>パスワード</span>

<input type="password" name="password" maxlength="30" required />

</label>


<br>

<label>

<span>自己紹介</span>

<textarea name="introd" maxlength="1000"></textarea>

</label>

<br>


<div class="img-input">

<input type="file" accept="image/*" name="image_file" id="image_input">

</div>

<br>


<input type="submit" class="submit" value="登録" />


</form>



<h2 class="sub-title">選択された画像</h2>

<div class="image-radius">

<img id="preview" style="display: none; height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">

<canvas id="canvas" style="display: none;"></canvas>

</div>


<hr>

<div class="link-text">


<a href="login.php">ユーザーログインへ</a>

</div>

</div>

<script>

let resizedBlob = null;

document.getElementById("image_input").addEventListener("change", async(e) => {

    const file = e.target.files[0];

    if (!file) return;

    const preview = document.getElementById("preview");

    preview.src = URL.createObjectURL(file);

    preview.style.display = "block";

    const bitmap = await createImageBitmap(file);

    const max = 1000;

    let w = bitmap.width;

    let h = bitmap.height;

    if (w > h && w > max) {

      h = h * (max / w);

      w = max;

    } else if (h > max) {

      w = w * (max / h);

      h = max;

    }

    const canvas = document.getElementById("canvas");

    canvas.width = w;

    canvas.height = h;

    const ctx = canvas.getContext("2d");

    ctx.drawImage(bitmap, 0, 0, w, h);

    canvas.toBlob((blob) => {
        const fileInput = document.getElementById("image_input");

        const newFile = new File([blob], "upload.png", { type: "image/png" });

        const dataTransfer = new DataTransfer();

        dataTransfer.items.add(newFile);

        fileInput.files = dataTransfer.files;

        }, "image/png", 0.9);

});



</script>


<?php
require("./end.php");

?>


