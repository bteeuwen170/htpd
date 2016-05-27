<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_TEACHER);

	if (isset($_POST["path"])) {
		$file = fopen($_POST["path"], "w") or
			die("Er is een fout opgeteden tijdens het opslaan.");
		fwrite($file, $_POST["data"]);
		fclose($file);
	} else {
		$path = URL_USERS . $_GET["path"];
	}
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="/include/lib/summernote/summernote.css">
		<link rel="stylesheet" href="/include/css/main.css">
		<link rel="stylesheet" href="/include/css/content.css">
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
				$("[data-tooltip='true']").tooltip({
					container: "body",
					trigger: "hover"
				});

				$("#seperator").mousedown(function (e)
				{
					e.preventDefault();
					$(document).mousemove(function (e)
					{
						e.preventDefault();
						var x = e.pageX - $(".left").offset().left;
						if (x > 300 && x < 3600 &&
								e.pageX < ($(window).width() - 300)) {
							$(".left").css("width", x);
							$(".right").css("margin-left", x + 16);
						}
					});
				});

				$(document).mouseup(function (e)
				{
					$(document).unbind("mousemove");
				});

				//if ($("#viewer").html()
				//		.indexOf("<!-- project: finished -->") == -1) //TODO Handle as well
				edit(0, true);
			});

			function save(reopen)
			{
				$("#editor").summernote("destroy");

				var data = new FormData();
				data.append("path",
						<?php echo("\"" . $path . PRJ_FILES[1] . "\""); ?>);
				data.append("data", $("#editor").html());

				var req = new XMLHttpRequest();
				req.open("post", "teacher.php", true);
				req.send(data);

				if (reopen) edit(0, true);
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<?php
				if (!file_exists($path))
					mkdir($path, 0755);

				echo("<div class='left'><div id='seperator'>
						</div><div id='editor'>");
				$fpath = $path . PRJ_FILES[1];
				for ($i = 0; $i < 2; $i++) { //FIXME Crappy f*cking code
					if (file_exists($fpath)) {
						$file = fopen($fpath, "r") or
							die("Er is een fout opgetreden!");
						if (filesize($fpath) > 0)
							echo(fread($file, filesize($fpath)));
						else
							echo("<br>");
						fclose($file);
					} else {
						touch($fpath);
						echo("<br>");
					}

					if (!$i) {
						$fpath = $path . PRJ_FILES[0];
						echo("
							</div></div>
							<div class='right'>
								<div id='optionbaralt'>
									<div class='btn-group'>
										<button
											class='btn btn-default btn-sm'
											title='Downloaden'
											data-tooltip='true'
											data-placement='bottom'
											onclick=\"download(-1, 'viewer')\">
											<span class='glyphicon
													glyphicon-download-alt'>
											</span>
										</button>
									</div>
								</div>
								<div id='viewer'>
						");
					}
				}
				echo("</div></div>");
			?>
			</div>
		</div>
	</body>
</html>
