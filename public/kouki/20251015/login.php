<?php
// エラー有無格納変数初期化
$err = null;

// DBに接続
$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (!empty($_POST["email"]) && !empty($_POST["password"])) {

    // SQL: ユーザー取得
    $sql = "SELECT * FROM users WHERE email = :email";
    $select_stmt = $dbh->prepare($sql);
    $select_stmt->execute([":email" => $_POST["email"]]);
    $result = $select_stmt->fetch(PDO::FETCH_ASSOC);

    // メール未登録ならリダイレクト（エラーコード1）
    if (empty($result)) {
        header("HTTP/1.1 303 See Other");
        header("Location: ./login.php?er=1");
        exit;
    }

    // パスワード検証
    if (!password_verify($_POST["password"], $result["password"])) {
        header("HTTP/1.1 303 See Other");
        header("Location: ./login.php?er=2");
        exit;
    }

    // セッションID処理
    $session_cookie_name = "session_id";
    $session_id = $_COOKIE[$session_cookie_name] ?? base64_encode(random_bytes(64));
    if (!isset($_COOKIE[$session_cookie_name])) {
        // 必要なら第3引数で有効期限等を指定
        setcookie($session_cookie_name, $session_id, time() + 3600, "/");
    }

    // Redis接続
    $redis = new Redis();
    $redis->connect("redis", 6379);

    $session_key = "session-" . $session_id;

    $session_values = [];
    if ($redis->exists($session_key)) {
        $session_values = json_decode($redis->get($session_key) ?? '[]', true);
    }

    // セッション更新
    $session_values["login_user_id"] = $result["id"];
    $session_values["login_user_name"] = $result["name"];
    $session_values["login_success"] = "ログインしました";

    $redis->set($session_key, json_encode($session_values));

    header("HTTP/1.1 303 See Other");
    header("Location: ./rename.php");
    exit;
}
?>

<h1>ログイン</h1>

<form method="post">
    <label>
        <span>メールアドレス</span>
        <input type="email" name="email" maxlength="256" required />
    </label>
    <br>
    <label>
        <span>パスワード</span>
        <input type="password" name="password" maxlength="30" minlength="4" required />
    </label>
    <br>
    <input type="submit" value="ログイン" />
</form>

<?php
// リダイレクトで来たエラーを表示
if (isset($_GET["er"])) {
    if ($_GET["er"] == 1) {
        echo "<p style='color:#F00;'>入力されたメールアドレスは登録されていません。</p>";
    } elseif ($_GET["er"] == 2) {
        echo "<p style='color:#F00;'>パスワードが間違っています。</p>";
    }
}
?>
