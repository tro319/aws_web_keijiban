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

$imageNames = [];

if (!empty($_FILES["image_files"]["name"][0])) {

	foreach ($_FILES["image_files"]["tmp_name"] as $i => $tmp) {

		if ($_FILES["image_files"]["error"][$i] != 0) continue;

		$imageName = time() . bin2hex(random_bytes(10)) . ".png";

		$filePath = "/var/www/public/upload/image/" . $imageName;

		if (move_uploaded_file($tmp, $filePath)) {

			$imageNames[] = $imageName;

		}

	}

}

$imageNames = array_slice($imageNames, 0, 3);
	
    



    $sql = "INSERT INTO board_posts (user_id, content, pic_name1, pic_name2, pic_name3) VALUES (:user_id, :content, :img1, :img2, :img3)";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ":user_id" => $loginID,
        ":content" => $content,
        ":img1" => $imageNames[0] ?? null,
		":img2" => $imageNames[1] ?? null,
		":img3" => $imageNames[2] ?? null,
    ]);

    $_SESSION["post_success"] = "投稿しました。";

    header("HTTP/1.1 303 See Other");
    header("Location: ./board.php");
    return;
    
}


require("./read.php");    
require("./header.php");
?>

<div class="container">

<h1 class="page-title">投稿フォーム</h1>

<form method="POST" enctype="multipart/form-data" class="form post-form">

    <label>

        <span>投稿文</span>

        <textarea name="content" required maxlength="1000" style="width: 300px; height: 100px;"></textarea>
        
    </label>
    
    <br>

    <label>

        <span>画像</span>

        <input type="file" accept="image/*" name="image_files[]" id="image_input" multiple />
        
    </label>

    <br>
        
    <input type="submit" class="submit" value="投稿" />

</form>



<h2 class="sub-title">選択された画像</h2>

<div class="images-cover post-images-cover">
    
    <ul class="images post-images" id="preview_list"></ul>

</div>

</div>


<script>

	let selectedFiles = [];
	
	let resizedBlob = null;

	document.getElementById("image_input").addEventListener("change", async(e) => {

		const files = Array.from(e.target.files);

		if (!files.length) return;

		const previewList = document.getElementById("preview_list");

		selectedFiles.push(...files);

		previewList.innerHTML = "";

		
		const max = 1000;

		const dataTransfer = new DataTransfer();

		for (const file of selectedFiles) {


			const li = document.createElement("li");

			const wrap = document.createElement("div");

			wrap.className = "image-box";

			const img = document.createElement("img");

			img.className = "preview-img";

			img.src = URL.createObjectURL(file);

			wrap.appendChild(img);

			li.appendChild(wrap);

			previewList.appendChild(li);
		

			// ここから画像リサイズ処理
		
			const bitmap = await createImageBitmap(file);

			let w = bitmap.width;

			let h = bitmap.height;


			if (w > h && w > max) {
	
				h = h * (max / w);
	
				w = max;
	
			} else if (h > max) {
	
				w = w * (max / h);
	
				h = max;
	
			}

			const canvas = document.createElement("canvas");

			canvas.width = w;

			canvas.height = h;

			const ctx = canvas.getContext("2d");

			ctx.drawImage(bitmap, 0, 0, w, h);

			const blob = await new Promise(res =>
				canvas.toBlob(res, "image/png", 0.9)
			);

			const newFile = new File([blob], "upload.png", {type: "image/png"});

			dataTransfer.items.add(newFile);

		}

		e.target.files = dataTransfer.files;



	});



</script>

<?php
require("./end.php");

?>
