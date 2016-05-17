<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_STUDENT))
		header("Location: /user/logout.php");

	if (isset($_POST["path"])) {
		$file = fopen($_POST["path"], "w") or
			die("Er is een fout opgeteden tijdens het opslaan.");
		fwrite($file, $_POST["data"]);
		fclose($file);
	} else {
		$path = URL_STORAGE . "students/" . $_GET["path"];
	}
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/css/content.css">
		<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="/include/lib/summernote/summernote.css">
		<link rel="stylesheet" href="/include/css/editor.css">
		<link rel="stylesheet" href="/include/css/manager.css">

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
				$("[data-tooltip='true']").tooltip({
					container: "body",
					trigger: "hover"
				});

				var path = <?php echo("\"" . $path . "\""); ?>;
				if (!path.endsWith(<?php echo("'" . PRJ_FILES[1] . "'"); ?>))
					if ($("#editor").html()
							.indexOf("<!-- project: finished -->") == -1)
						edit(1);
			});

			function save(reopen)
			{
				$("#editor").summernote("destroy");

				if (!reopen)
					$("#editor").prepend("<!-- project: finished -->");

				var data = new FormData();
				data.append("path", <?php echo("\"" . $path . "\""); ?>);
				data.append("data", $("#editor").html());

				var req = new XMLHttpRequest();
				req.open("post", "editor.php", true);
				req.send(data);

				if (reopen) edit(1);
			}

			function finish()
			{
				save(0);
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<div id="optionbar">
				<button
					class="btn btn-default btn-sm"
					title="Downloaden"
					data-tooltip="true"
					data-placement="bottom"
					onclick="download(-1)">
					<span class="glyphicon glyphicon-download-alt"></span>
				</button>
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
