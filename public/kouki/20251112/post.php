<?php
session_start();

$loginID = $_SESSION["login_id"];

if ($loginID == null) {

  header("HTTP/1.1 303 See Other");
  header("Location: ./login.php");
  return;

}


$dbh = new PDO("mysql:host=mysql;dbname=example_db", "root", "");


if (!empty($_POST)) {

// 投稿処理
if (!empty($_POST["content"])) {

    $content = htmlspecialchars($_POST["content"]);

}




// 画像取得

$imageName = null;

if ($_FILES["image_file1"]["error"] == 0) {

    $tmp = $_FILES["image_file1"]["tmp_name"];

    $imageName1 = time() . bin2hex(random_bytes(25)) . ".png";

    $filePath = "/var/www/public/upload/image/" . $imageName1;

    $success = move_uploaded_file($tmp, $filePath);

    if (!$success) {

        var_dump("move_failed", $tmp, $filePath, error_get_last());

    }

    $_SESSION["post_img1"] = $imageName1;
    
}


    $sql = "INSERT INTO board_posts (user_id, content, pic_name1) VALUES (:user_id, :content, :img1)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ":user_id" => $loginID,
        ":content" => $content,
        ":img1" => $imageName1,
    ]);

    $_SESSION["post_success"] = "投稿しました。";

    header("HTTP/1.1 303 See Other");
    header("Location: ./board.php");
    return;
    
}
    
?>

<h1>投稿フォーム</h1>

<form method="POST" enctype="multipart/form-data">

    <label>

        <span>投稿文:</span>

        <textarea name="content" required maxlength="1000" style="width: 300px; height: 100px;"></textarea>
        
    </label>
    
    <br>

    <label>

        <span>画像1</span>

        <input type="file" accept="image/*" name="image_file1" id="image_input1" />
        
    </label>

    <br>
        
    <input type="submit" value="投稿" />

</form>



<h2 class="sub-title">選択された画像</h2>

<div class="images-cover post-images-cover">
    
    <ul class="images post-images">

        <li>

            <div class="image-radius">

                
                <img id="preview_img1" style="display: none; height: 5em; width: 5em; border-radius: 50%; object-fit: cover;">

                <canvas id="canvas_img1" style="display: none;"></canvas>
                

            </div>
            
        </li>

    </ul>

</div>


<p><a href="board.php">▶ 掲示板に戻る</a></p>


<script>

	let resizedBlob = null;

	document.getElementById("image_input1").addEventListener("change", async(e) => {

		const file = e.target.files[0];

		if (!file) return;

		const preview = document.getElementById("preview_img1");

		preview.src = URL.createObjectURL(file);

		preview.style.display = "block";

		const bitmap = await createImageBitmap(file);

		const max = 1000;

		let w = bitmap.width;

		let h = bitmap.height;

		if (w > h && w > max) {

			h = h * (max / w);

			w = max;

		} else if (h > max) {

			w = w * (max / h);

			h = max;

		}

		const canvas = document.getElementById("canvas_img1");

		canvas.width = w;

		canvas.height = h;

		const ctx = canvas.getContext("2d");

		ctx.drawImage(bitmap, 0, 0, w, h);

		canvas.toBlob((blob) => {
            
			const fileInput = document.getElementById("image_input1");

			const newFile = new File([blob], "upload.png", { type: "image/png" });

			const dataTransfer = new DataTransfer();

			dataTransfer.items.add(newFile);

			fileInput.files = dataTransfer.files;

		}, "image/png", 0.9);

	});



</script>
