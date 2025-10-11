<?php

// セッションIDの取得(なければ新規作成)

$session_cookie_name = "session_id";

$session_id = $_COOKIE[$session_cookie_name] ?? base64_encode(random_bytes(64));

if (!isset($_COOKIE[$session_cookie_name])) {

    setcookie($session_cookie_name, $session_id);

}


// redisコンテナ接続

$redis = new Redis();

$redis->connect("redis", 6379);


// redisにセッション変数を保存するキーを決める


$redis_session_key = "session-" . $session_id;



$session_values = $redis->exists($redis_session_key) ? json_decode($redis->get($redis_session_key), true) : [];



// セッション変数からカウントを取得。なければ初期値0に設定


$count = isset($session_values["count"]) ? intval($session_values["count"]) : 0;

$count++;

$session_values["count"]  = strval($count);

$redis->set($redis_session_key, json_encode($session_values));



// 表示


print("このセッション内では、" . strval($count) . "回目のアクセスです。");





