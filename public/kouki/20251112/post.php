<?php
session_start();

$loginID = $_SESSION["login_id"] ?? "";

$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");

if (empty($loginID)) {
    header("HTTP/1.1 303 See Other");
    header("Location: ./login.php");
    exit;
}

// 投稿処理
if (!empty($_POST["content"])) {

    $content = htmlspecialchars($_POST["content"]);

    $sql_insert = "INSERT INTO board_posts (user_id, content) VALUES (:uid, :content)";
    $stmt_insert = $dbh->prepare($sql_insert);
    $stmt_insert->execute([
        ":uid" => $loginID,
        ":content" => $content,
    ]);

    $_SESSION["post_success"] = "投稿しました。";

    header("HTTP/1.1 303 See Other");
    header("Location: ./board.php");
    exit;
}
?>

<h1>投稿フォーム</h1>

<form method="POST">
    <textarea name="content" required maxlength="1000" style="width: 300px; height: 100px;"></textarea>
    <br>
    <button type="submit">投稿</button>
</form>

<p><a href="board.php">▶ 掲示板に戻る</a></p>
