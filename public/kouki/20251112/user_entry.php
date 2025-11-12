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
	
	$hash_pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

	// sql
	
	$sql = "INSERT INTO users (name, email, password) VALUES(:user_name, :email, :password)";

	// INSERTする
	
	$insert_stmt = $dbh->prepare($sql);

	$insert_stmt->execute([
		":user_name" => $_POST["user_name"],
		":email" => $_POST["email"],
		":password" => $hash_pass,
	]);
	header("HTTP/1.1 303 See Other");
	header("Location: ./user_finish.php");
	return;

}

?>


<h1>会員登録</h1>

	<form method="post">

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


		<input type="submit" value="登録" />


	</form>


<?php if (!empty($_GET["err"]) && $_GET["err"] == 1): ?>

  <p style="color: #F00;">入力されたメールアドレスはすでに登録されています。</p>

<?php endif; ?>



