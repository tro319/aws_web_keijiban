<?php

// エラー有無格納変数初期化

$err = null;  

// DBに接続

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");


  $session_cookie_name = "session_id";

  // セッションidなければ作成

  $session_id = $_COOKIE[$session_cookie_name];
  
if (!isset($_COOKIE[$session_cookie_name])) {
  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  return;

}


if (!empty($_POST["user_name"])) {

// 対象のユーザー情報取得(SELECT) 

$redis = new Redis();

$redis->connect("redis", "6379");

$session_key = "session-" . $session_id;

$session_values = $redis->exists($session_key);

if ($session_values["log_user_id"]) {

  $sql_get = "SELECT *  FROM users WHERE id = :id";

  $stmt_get = $dbh->prepare($sql_get);

  $stmt_get->execute([
    ":id" => $session_values["log_user_id"],
  ]);

  $result_get = $stmt_get->fetch();

  $sql_update = "UPDATE users SET name = :user_name WHERE id = :id";

  $stmt_update = $dbh->prepare($sql_update);

  $stmt_update->execute([
    ":id" => $session_values["log_user_id"],
    ":user_name" => $_POST["user_name"],
  ]);

  $session_values["log_user_name"] = $_POST["user_name"];

$session_values["update_success"] = "会員情報更新しました";

$redis->set($session_key, json_encode($session_values);
header("HTTP/1.1 303 See Other");
header("Location: ./rename.php");
return;

}

?>


<h1>ユーザー名更新</h1>

<?php foreach ($result_get as $rg): ?>

<div style="padding: 20px;">

  <p><?php echo $rg["name"]; ?>

  <p><?php echo $rg["email"]; ?>

</div>

<?php endforeach; ?>

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
