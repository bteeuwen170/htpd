<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_STUDENT);

	$path = URL_USERS . $_COOKIE["uid"] . "/" . CV_NAME;

	if (isset($_POST["data"])) {
		$file = fopen($path, "w") or
			die("Er is een fout opgeteden tijdens het opslaan.");
		fwrite($file, $_POST["data"]);
		fclose($file);
	}
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/lib/bootstrap/bootstrap.css">
		<link rel="stylesheet" href="/include/lib/summernote/summernote.css">
		<link rel="stylesheet" href="/include/css/main.css">
		<link rel="stylesheet" href="/include/css/content.css">
		<link rel="stylesheet" href="/include/css/editor.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/bootstrap.js"></script>
		<script type="text/javascript"
				src="/include/lib/summernote/summernote.js"></script>
		<script type="text/javascript"
				src="/include/js/summernote.js"></script>
		<script type="text/javascript"
				src="/include/lib/html-docx-js/html-docx.js"></script>
		<script type="text/javascript"
				src="/include/lib/filesaver/filesaver.js"></script>

		<script type="text/javascript">
			$(document).ready(function() 
			{
				init(0, "Curriculum Vitae");

				edit();
			});

			function save()
			{
				$("#editor").summernote("destroy");

				var data = new FormData();
				data.append("data",
						document.getElementById("editor").innerHTML);

				var req = new XMLHttpRequest();
				req.open("post", "cv.php", true);
				req.send(data);

				edit();
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<div id="editor">
				<?php
					if (file_exists($path)) {
						$file = fopen($path, "r") or
							die("Er is een fout opgetreden!");
						if (filesize($path) > 0)
							echo(fread($file, filesize($path)));
						else
							echo("<br>");
						fclose($file);
					} else {
						touch($path);
						echo("<br>");
					}
				?>
			</div>
		</div>
	</body>
</html>
