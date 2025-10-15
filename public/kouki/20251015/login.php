<?php

// エラー有無格納変数初期化

$err = null;  

// DBに接続

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (!empty($_POST["email"]) && !empty($_POST["password"])) {


	// パスワードハッシュ化
	
	$hash_pass = password_hash($_POST["password"], PASSWORD_DEFAULT);

	// sql
	
	$sql = "SELECT * FROM users WHERE email = :email";

	// SELECTする
	
	$select_stmt = $dbh->prepare($sql);

	$select_stmt->execute([
		":email" => $_POST["email"],
	]);

  $result = $select_stmt->fetch();

  if (empty($result)) {

    $err = "入力されたメールアドレスは登録されていません。";
    
    if (!empty($err)) {
      header("HTTP/1.1 303 See Other");
      header("Location: ./login.php?er=1");
      return;

  }



  $corre_pass = password_verify($_POST["password"], $result["password"]);

  if ($corre_pass == false) {
    
    $err = "パスワードが間違っています";

    if (!empty($err)) {
      header("HTTP/1.1 303 See Other");
      header("Location: login.php?er=2");
      return;

  }

}

  $session_cookie_name = "session_id";

  // セッションidなければ作成

  $session_id = $_COOKIE[$session_cookie_name] ?? base64_encode(random_bytes(64));
  
if (!isset($_COOKIE[$session_cookie_name])) {

  setcookie($session_cookie_name, $session_id);

}


$redis = new Redis();

$redis->connect("redis", "6379");

$session_key = "session-" . $session_id;

$session_values = $redis->exists($session_key) ? json_decode($redis->get($session_key), true) : [];

$session_values["login_user_id"] = $result["id"];

$session_values["login_user_name"] = $result["name"];

$session_values["login_success"] = "ログインしました";

$redis->set($session_key, json_encode($session_values));
header("HTTP/1.1 303 See Other");
header("Location: ./rename.php");
return;

}
}

?>


<h1>ログイン</h1>

	<form method="post">

		<label>

			<span>メールアドレス</span>

			<input type="email" name="email" maxlength="256"  required />
			

		</label>



		<br>

		<label>

			<span>パスワード</span>
	
			<input type"password" name="password" maxlength="30" minlength="4" required />

		</label>


		<br>

		


		<input type="submit" value="ログイン" />


	</form>


  <!-- エラー表示 -->

<?php if (!empty($err)): ?>

  <p style="color: #F00;"><?php echo $err; ?></p>

 

<?php endif; ?>
