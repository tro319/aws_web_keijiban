<?php
session_start();

// エラー有無格納変数初期化

$err = null; 

// DBに接続

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (!empty($_POST["user_name"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {


  // メールアドレス重複チェック
  
  $sql_email = "SELECT email FROM users WHERE email = :email";

  $stmt_email = $dbh->prepare($sql_email);

  $stmt_email->execute([
    ":email" => $_POST["email"],
  
  ]);

  $result_check = $stmt_email->fetch();


  if (!empty($result_check)) {

    $err = "メールアドレスが重複しています。";

    if (!empty($err)) {
      header("HTTP/1.1 303 See Other");
      header("Location: ./user_entry.php?err=1");
      return;


    }
	  
  }

	// パスワードハッシュ化
	
	$hashPass = password_hash($_POST["password"], PASSWORD_DEFAULT);

	// 画像取得

	$imageName = null;

	if (!empty($_FILES["image_file"])) {

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
	
	$insert_stmt = $dbh->prepare($sql);

	$insert_stmt->execute([
		":user_name" => $_POST["user_name"],
		":email" => $_POST["email"],
		":password" => $hashPass,
		":introd" => $introd,
		":img" => $imageName,
	]);
	
	header("HTTP/1.1 303 See Other");
	header("Location: ./user_finish.php");
	return;

}

?>


<h1>ユーザー登録</h1>

	<form method="post" enctype="multipart/form-data">

		<label>

			<span>名前</span>

			<input type="text" name="user_name" maxlength="50"  required />
			

		</label>



		<br>

		<label>

			<span>メールアドレス</span>
	
			<input type"email" name="email" maxlength="256" required />

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


		<input type="submit" value="登録" />


	</form>

	<canvas id="canvas" style="display: none;"></canvas>

	<h2 class="sub-title">選択された画像</h2>

	<div class="image-radius">
	    
	    <img id="preview" style="display: none; height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">
		
	</div>


<?php if (!empty($_GET["err"]) && $_GET["err"] == 1): ?>

  <p style="color: #F00;">入力されたメールアドレスはすでに登録されています。</p>

<?php endif; ?>

<div class="link-text pre-link">

	<a href="login.php">ユーザーログインへ</a>

</div>


<script>

	document.getElementById("image_input").addEventListener("change", function(e) {

		const file = e.target.files[0];

		if (!file) return;

		const preview = document.getElementById("preview");

		preview.src = URL.createObjectURL(file);

		preview.style.display = "block";

	});

</script>



