<?php

$redis = new Redis();

$redis->connect("redis", 6379);

$key = "simple_comment";

$comment = $redis->exists($key) ? $redis->get($key) : "";



?>

<form method="post" action="">

	<input name="content" id="content" maxlength="30" required />

</form>


<p>投稿文 「<?php echo htmlspecialchars($latestPost, ENT_QUOTES, "UTF-8"); ?>」</p>
