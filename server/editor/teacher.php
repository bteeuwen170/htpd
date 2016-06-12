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
		$path = URL_USERS . $_GET["uid"] . "/" . $_GET["pid"] . "/";
		$v0path = $path . PRJ_FILES[0];
		$v1path = $path . PRJ_FILES[1];
		$epath = $path . PRJ_FILES[2];
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

				init(1);

				if ($("#editor").html()
					.indexOf("<?php echo(HEADER_FIN); ?>") == -1) {
					edit();
					document.getElementById("optionbar").style.display =
					"none";
				}
			});

			function save(reopen)
			{
				$("#editor").summernote("destroy");

				if (!reopen)
					$("#editor").prepend("<?php echo(HEADER_FIN); ?>");

				var data = new FormData();
				data.append("path",
						<?php echo("\"" . $epath . "\""); ?>);
				data.append("data", $("#editor").html());

				var req = new XMLHttpRequest();
				req.open("post", "teacher.php", true);
				req.send(data);

				if (reopen)
					edit();
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<?php
				$avail = 0;

				echo("
					<div class='path'>" .
						$_GET["path"] .
					"</div>
				");

				echo("<h4 class='titlealt'>" . PRJ_NAMES[0]);
				if (file_exists($v0path)) {
					$v0file = fopen($v0path, "r") or
						die("Er is een fout opgetreden!");
					if (filesize($v0path) > 0) {
						$v0contents = fread($v0file, filesize($v0path));
						if (strpos($v0contents, HEADER_FIN) === false)
							echo(" - Niet ingeleverd");
						echo("
							</h4>
							<div class='optionbaralt'>
								<div class='btn-group'>
									<button
										class='btn btn-default btn-sm'
										title='Downloaden'
										data-tooltip='true'
										data-placement='bottom'
										onclick=\"download('viewer0')\">
										<span class='glyphicon
												glyphicon-download-alt'>
										</span>
									</button>
								</div>
							</div>
							<div class='viewer' id='viewer0'>
						");
						echo($v0contents);
						echo("</div>");
					} else {
						$avail = 1;
					}

					fclose($v0file);
				} else {
					$avail = 1;
				}

				if ($avail == 1) {
					echo("
						</h4>
						<div class='viewer' id='viewer0'>
							" . PRJ_NAMES[0] . " is nog niet beschikbaar.
						</div>
					");
				}

				echo("<h4>" . PRJ_NAMES[1]);
				if (file_exists($v1path)) {
					$v1file = fopen($v1path, "r") or
						die("Er is een fout opgetreden!");
					if (filesize($v1path) > 0) {
						$v1contents = fread($v1file, filesize($v1path));
						if (strpos($v1contents, HEADER_FIN) === false)
							echo(" - Niet ingeleverd");
						echo("
							</h4>
							<div class='optionbaralt'>
								<div class='btn-group'>
									<button
										class='btn btn-default btn-sm'
										title='Downloaden'
										data-tooltip='true'
										data-placement='bottom'
										onclick=\"download('viewer1')\">
										<span class='glyphicon
												glyphicon-download-alt'>
										</span>
									</button>
								</div>
							</div>
							<div class='viewer' id='viewer1'>
						");
						echo($v1contents);
						echo("</div>");
					} else {
						$avail = 2;
					}

					fclose($v1file);
				} else {
					$avail = 2;
				}

				if ($avail == 2) {
					echo("
						</h4>
						<div class='viewer' id='viewer1'>
							" . PRJ_NAMES[1] . " is nog niet beschikbaar.
						</div>
					");
				}

				if (!$avail) {
					echo("
						<h4>Feedback</h4>
						<div id='optionbar'>
							<div class='btn-group'>
								<button
									class='btn btn-default btn-sm'
									title='Bewerken'
									data-tooltip='true'
									data-placement='bottom'
									onclick=\"edit()\">
									<span class='glyphicon
											glyphicon-pencil'></span>
								</button>
								<button
									class='btn btn-default btn-sm'
									title='Downloaden'
									data-tooltip='true'
									data-placement='bottom'
									onclick=\"download('editor')\">
									<span class='glyphicon
											glyphicon-download-alt'></span>
								</button>
							</div>
						</div>
						<div id='editor'>
					");
					if (file_exists($epath)) {
						$efile = fopen($epath, "r") or
							die("Er is een fout opgetreden!");
						if (filesize($epath) > 0)
							echo(fread($efile, filesize($epath)));
						else
							echo("<br>");
						fclose($efile);
					} else {
						touch($epath);
						echo("<br>");
					}
					echo("</div>");
				}
			?>
		</div>
	</body>
</html>
