<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_STUDENT);

	if (isset($_POST["pid"]) && isset($_POST["file"])) {
		if (!is_numeric($_POST["pid"]) || !is_numeric($_POST["file"])) {
			header("HTTP/1.0 403 Forbidden");
			header("Location: /include/html/403.html");
		}

		//TODO Check if doesn't contain project: finished header XXX XXX XXX

		$path = URL_USERS . $_COOKIE["uid"] . "/" . $_POST["pid"] . "/" .
				PRJ_FILES[$_POST["file"]];

		$file = fopen($path, "w") or
			die("Er is een fout opgeteden tijdens het opslaan.");
		fwrite($file, $_POST["data"]);
		fclose($file);
	} else {
		if (!is_numeric($_GET["pid"]) || !is_numeric($_GET["file"])) {
			header("HTTP/1.0 403 Forbidden");
			header("Location: /include/html/403.html");
		}

		$path = URL_USERS . $_COOKIE["uid"] . "/" . $_GET["pid"] . "/" .
				PRJ_FILES[$_GET["file"]];
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
				$("[data-tooltip='true']").tooltip({
					container: "body",
					trigger: "hover"
				});

				var path = <?php echo("\"" . $path . "\""); ?>;
				if (<?php echo($_GET["file"]); ?> == 0) {
					if ($("#editor").html()
							.indexOf("<!-- project: finished -->") == -1)
						edit(1);
				} else if (<?php echo($_GET["file"]); ?> == 1) {
					if ($("#editor").html()
							.indexOf("<!-- project: finished -->") == -1) {
						document.getElementById("editor").innerHTML =
							"Feedback is nog niet beschikbaar.";
						document.getElementById("optionbar").style.display =
							"none";
					}
				}
			});

			function save(reopen)
			{
				$("#editor").summernote("destroy");

				if (!reopen)
					$("#editor").prepend("<!-- project: finished -->");

				var data = new FormData();
				data.append("pid",
						<?php echo("\"" . $_GET["pid"] . "\""); ?>);
				data.append("file",
						<?php echo("\"" . $_GET["file"] . "\""); ?>);
				data.append("data",
						document.getElementById("editor").innerHTML);

				var req = new XMLHttpRequest();
				req.open("post", "student.php", true);
				req.send(data);

				if (reopen) edit(1);
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<div class="noselect" id="optionbar">
				<div class="btn-group">
					<button
						class="btn btn-default btn-sm"
						title="Downloaden"
						data-tooltip="true"
						data-placement="bottom"
						onclick="download(-1)">
						<span class="glyphicon glyphicon-download-alt"></span>
					</button>
				</div>
			</div>
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
						$dirpath = preg_replace("/\/[^\/]*$/", "", $path);
						if (!file_exists($dirpath))
							mkdir($dirpath, 0755);

						touch($path);
						echo("<br>");
					}
				?>
			</div>
		</div>
	</body>
</html>
