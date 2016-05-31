<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_TEACHER);
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" type="text/css"
				href="/include/lib/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/content.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/bootstrap.js"></script>
		<script type="text/javascript"
				src="/include/lib/tablesorter/tablesorter.js"></script>
		<script type="text/javascript"
				src="/include/js/table.js"></script>

		<script type="text/javascript">
			$(document).ready(function()
			{
				$("#userlist").tablesorter();

				$(".pr").click(function()
				{
					location.href =
						"/editor/teacher.php?pid=<?php echo($_GET["pid"]); ?>" +
								"&uid=" +
								$(this).closest("tr").attr("id").slice(1);
				});
			});
		</script>
	</head>
	<body>
		<div class="wrapper">
			<div class="datacontainer">
				<table class="table table-striped tablesorter" id="userlist">
					<thead>
						<tr>
							<th>ID</th>
							<th>Naam</th>
						</tr>
					</thead>
					<?php
						$users = array();

						$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
						DB_USER, DB_PASS, DB_NAME);
						check($dbconn, !$dbconn->connect_error, false);

						$qurows = sprintf("SELECT uid, name FROM %s", DB_USERS);
						$urows = $dbconn->query($qurows);
						check($dbconn, $urows, false);

						$qgrows =
							sprintf("SELECT pid, grp, uid FROM %s WHERE pid=%s
									AND grp=%s", DB_GROUPS, $_GET["pid"],
									$_GET["grp"]);
						$grows = $dbconn->query($qgrows);
						check($dbconn, $grows, false);

						while ($grow = $grows->fetch_array())
							array_push($users, $grow["uid"]);

						while ($urow = $urows->fetch_array()) {
							if (in_array($urow["uid"], $users)) {
								echo("<tr id='u" . $urow["uid"] . "'>");
								echo("<td class='pr'>" .
										$urow["uid"] . "</td>");
								echo("<td class='pr'>" .
										$urow["name"] . "</td>");
								echo("</tr>");
							}
						}
						
						$grows->close();
						$urows->close();
						$dbconn->close();
					?>
				</table>
			</div>
		</div>
	</body>
</html>
