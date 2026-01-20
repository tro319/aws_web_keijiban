<?php
session_start();


// ログインチェック

  $loginID = $_SESSION["login_id"] ?? "";


  if ($loginID == "") {

    header("HTTP/1.1 303 See Other");
    header("Location: ./login.php");
	return;

  }

// DBに接続

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (!empty($_POST["user_name"]) && !empty($_POST["email"])) {


  // ユーザーネーム重複チェック
  
  $sql_name = "SELECT name FROM users WHERE name = :user_name AND id != :id";

  $stmt_name = $dbh->prepare($sql_name);

  $stmt_name->execute([

    ":user_name" => $_POST["user_name"],
    ":id" => $loginID,

  ]); 


  $name_check = $stmt_name->fetch();

  if (!empty($name_check)) {

      header("HTTP/1.1 303 See Other");
      header("Location: ./prof_update.php?err=name");  
	  return;


  }

  // メールアドレス重複チェック
  
  $sql_email = "SELECT email FROM users WHERE email = :email AND id != :id";

  $stmt_email = $dbh->prepare($sql_email);

  $stmt_email->execute([

    ":email" => $_POST["email"],
    ":id" => $loginID,
  
  ]);

  $email_check = $stmt_email->fetch();


  if (!empty($email_check)) {

      header("HTTP/1.1 303 See Other");
      header("Location: ./prof_update.php?err=email");
      return;

	  
  }

}

  if (!empty($_POST["password"])) {


	  // パスワードハッシュ化
	
  	$hashPass = password_hash($_POST["password"], PASSWORD_DEFAULT);

  }

	// 画像取得

	$imageName = null;

	if (isset($_FILES["image_file"]) && $_FILES["image_file"]["error"] == 0) {

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


  if (!empty($_POST)) {	

	  // sql
	
	  $sql = "UPDATE users SET name = :user_name, email = :email, password = :password, introd = :introd, img_name = :img WHERE id = :id";

	  // UPDATEする
	
	  $update_stmt = $dbh->prepare($sql);

	  $update_stmt->execute([
	  	":user_name" => $_POST["user_name"],
		  ":email" => $_POST["email"],
		  ":password" => $hashPass,
		  ":introd" => $introd,
		  ":img" => $imageName,
      	  ":id" => $loginID,
	  ]);

      
	
	  header("HTTP/1.1 303 See Other");
	  header("Location: ./prof_update.php?result=success");
	  return;

  

}

$sql = "SELECT * FROM users WHERE id = :id";

$get_stmt = $dbh->prepare($sql);

$get_stmt->execute([
  
  ":id" => $loginID,

]);

$get_result = $get_stmt->fetchAll();

?>


<h1>ユーザー更新フォーム</h1>



<?php if (!empty($_GET["result"]) && $_GET["result"] == "success"): ?>

  <p style="color: #0F0;">ユーザー情報更新が完了しました。</p>

<?php endif; ?>


<?php if (!empty($_GET["err"]) && $_GET["err"] == "name"): ?>

  <p style="color: #F00;">入力されたユーザーネームはすでに登録されています。</p>

<?php endif; ?>


<?php if (!empty($_GET["err"]) && $_GET["err"] == "email"): ?>

  <p style="color: #F00;">入力されたメールアドレスはすでに登録されています。</p>

<?php endif; ?>



	<form method="post" enctype="multipart/form-data">

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


		<input type="submit" value="更新" />


	</form>

	<h2 class="sub-title">選択された画像</h2>

	<div class="image-radius">
	    
	    <img id="preview" style="display: none; height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">

		<canvas id="canvas" style="display: none;"></canvas>
		
	</div>


  <hr>

  <h2 class="sub-title">ユーザー情報</h2>
  
  <div class="user-info">


      <?php foreach ($get_result as $profile): ?>

        <p>ユーザーネーム: <?= htmlspecialchars($profile["name"]) ?></p>

        <p>メールアドレス: <?= htmlspecialchars($profile["email"]) ?></p>

        <?php if ($profile["introd"] != null): ?>

          <p>自己紹介: <?= htmlspecialchars($profile["introd"]) ?></p>

        <?php endif; ?>

        <?php if ($profile["img_name"] != null): ?>

          <div class="image-radius">

            <img src="/upload/image/<?= htmlspecialchars($profile["img_name"]) ?>" style="display: none; height: 5em; width: 5em; border-radius: 50%; object-fit: cover;" />

          </div>

        <?php endif; ?>

      <?php endforeach; ?>
   
  </div>



<div class="link-text pre-link">

	<a href="login.php">ユーザーログインへ</a>

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



