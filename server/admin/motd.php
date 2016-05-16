<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_ADMIN))
		header("Location: /user/logout.php");

	if (isset($_POST["data"])) {
		$motd = fopen(URL_STORAGE . "motd", "w") or
			die("Er is een fout opgeteden tijdens het opslaan.");
		fwrite($motd, $_POST["data"]);
		fclose($motd);
	}
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/css/content.css">
		<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="/include/lib/summernote/summernote.css">
		<link rel="stylesheet" href="/include/css/editor.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript"
				src="/include/lib/summernote/summernote.js"></script>
		<script type="text/javascript"
				src="/include/js/summernote_config.js"></script>
		<script type="text/javascript"
				src="/include/lib/html-docx-js/html-docx.js"></script>
		<script type="text/javascript"
				src="/include/lib/filesaver/filesaver.js"></script>

		<script type="text/javascript">
			$(document).ready(function() 
			{
				edit(0);
			});

			function save()
			{
				$("#editor").summernote("destroy");

				var data = new FormData();
				data.append("data",
					document.getElementById("editor").innerHTML);
				var req = new XMLHttpRequest();
				req.open("post", "motd.php", true);
				//req.setRequestHeader("Content-type",
				//	"application/x-www-form-urlencoded"); //TODO Encode
				req.send(data);

				edit(0);
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<div id="editor">
				<?php
					$path = URL_STORAGE . "motd";
					if (file_exists($path)) {
						$motd = fopen($path, "r") or
							die("Er is een fout opgetreden!");
						if (filesize($path) > 0)
							echo(fread($motd, filesize($path)));
						else
							echo("<br>");
						fclose($motd);
					} else {
						touch($path);
						echo("<br>");
					}
				?>
			</div>
		</div>
	</body>
</html>
