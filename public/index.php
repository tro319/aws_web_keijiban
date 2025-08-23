<?php
session_start();


require("library.php");


if (!empty($_SESSION["login_result"])) {

  $result = h_s($_SESSION["login_result"]);

}

?>


<script>

  window.alert(<?php echo $result; ?>);

</script>




