<?php
session_start();

// エラー有無格納変数初期化
$err = null;

// DBに接続
$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");


// ユーザーIDがセッションに無ければログイン画面へ
if (empty($_SESSION["login_id"])) {
    header("HTTP/1.1 303 See Other");
    header("Location: ./login.php");
    exit;
}

// POSTでユーザー名が送信されたら更新
if (!empty($_POST["user_name"])) {

    $sql_update = "UPDATE users SET name = :user_name WHERE id = :id";
    $stmt_update = $dbh->prepare($sql_update);
    $stmt_update->execute([
        ":id" => $_SESSION["login_id"],
        ":user_name" => $_POST["user_name"],
    ]);

    // セッション更新
    $_SESSION["login_user_name"] = $_POST["user_name"];
    $_SESSION["update_success"] = "会員情報を更新しました";

    header("HTTP/1.1 303 See Other");
    header("Location: ./rename.php");
    exit;
}

// DBからユーザー情報取得
$sql_get = "SELECT * FROM users WHERE id = :id";
$stmt_get = $dbh->prepare($sql_get);
$stmt_get->execute([":id" => $_SESSION["login_id"]]);
$result_get = $stmt_get->fetch(PDO::FETCH_ASSOC);

?>

<h1>ユーザー名更新</h1>

<?php if (!empty($_SESSION["update_success"])): ?>
    <p style="color:green;"><?php echo $_SESSION["update_success"]; ?></p>
    <?php unset($_SESSION["update_success"]); ?>
<?php endif; ?>

<div style="padding:20px;">
    <p>名前: <?php echo htmlspecialchars($result_get["name"], ENT_QUOTES); ?></p>
    <p>メール: <?php echo htmlspecialchars($result_get["email"], ENT_QUOTES); ?></p>
</div>

<form method="post">
    <label>
        <span>新しいユーザー名</span>
        <input type="text" name="user_name" maxlength="50" required />
    </label>
    <br>
    <input type="submit" value="更新する" />
</form>
