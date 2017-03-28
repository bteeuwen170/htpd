<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_STUDENT);
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" type="text/css" 
				href="/include/lib/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/content.css">
		<link rel="stylesheet" type="text/css" href="/include/css/home.css">
	</head>
	<body>
		<div class="wrapper">
			<table class="home-table" width="100%">
				<tr>
					<?php
						$path = URL_STORAGE . MOTD_FILE;

						if (file_exists($path)) {
							$motd = fopen($path, "r") or
								die("Er is een fout opgetreden!");
							if (filesize($path) > 0)
								echo("
									<td>
										<div class='panel panel-default'>
											<div class='panel-heading'>
												<h3 class='panel-title'>
													Welkom
												</h3>
											</div>
											<div class='panel-body'>" .
												fread($motd, filesize($path)) .
											"</div>
										</div>
									</td>
								");

							fclose($motd);
						}
					?>
					<!--<td>
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Laatste feedback</h3>
							</div>
							<div class="panel-body">
								TODO
							</div>
						</div>
					</td>-->
				</tr>
				<tr>
					<?php
						if ($_COOKIE["gid"] == GID_STUDENT)
							echo("
								<td>
									<div class='panel panel-default'>
										<div class='panel-heading'>
											<h3 class='panel-title'>
												Portfolio
											</h3>
										</div>
										<div class='panel-body'>
											<a href='portfolio.php'>
												Portfolio Downloaden
											</a>
										</div>
									</div>
								</td>
							");
					?>
				</tr>
			</table>
		</div>
	</body>
</html>
