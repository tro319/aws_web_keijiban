<?php
session_start();


require("library.php");


if (!empty($_SESSION["login_result"])) {

  $result = h_s($_SESSION["login_result"]);

}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>web掲示板</title>
</head>
<body>
    
</body>
</html>


<script>

  window.alert("<?php echo $result; ?>");

</script>



