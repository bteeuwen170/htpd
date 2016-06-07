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

		<script type="text/javascript" src="/include/js/main.js"></script>
		<script type="text/javascript" src="/include/js/groups.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<form method="post" action="groupmod.php">
				<div class="noselect" id="optionbar">
					<div class="btn-group">
						<button
							type="submit"
							class="btn btn-default btn-sm"
							name="save"
							title="Opslaan"
							data-tooltip="true"
							data-placement="bottom">
							<span class="glyphicon glyphicon-floppy-disk">
							</span>
						</button>
					</div>
					<div class="btn-group">
						<button
							type="button"
							class="btn btn-default btn-sm"
							title="Toevoegen"
							data-toggle="modal"
							data-tooltip="true"
							data-placement="bottom"
							data-backdrop="static"
							data-target="#created">
							<span class="glyphicon glyphicon-plus"></span>
						</button>
					</div>
					<div class="btn-group">
						<button
							type="submit"
							class="btn btn-default btn-sm"
							id="delete"
							name="delete"
							title="Verwijderen"
							data-tooltip="true"
							data-placement="bottom" disabled>
							<span class="glyphicon glyphicon-trash"></span>
						</button>
					</div>
				</div>
				<div class="datacontainer">
					<table
						class="table table-striped tablesorter"
						id="grouplist">
						<thead class="nopointer noselect">
							<tr>
								<th>
									<input
										type="checkbox"
										id="sall">
								</th>
								<th>ID</th>
								<th>Naam</th>
								<th>Groep</th>
							</tr>
						</thead>
						<?php
							echo("
								<input
									type='hidden'
									name='pid'
									value='" . $_GET["pid"] . "'>
							");

							$users = array();

							$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
							check($dbconn, !$dbconn->connect_error, false);

							$qprows = sprintf("SELECT groups FROM %s
									WHERE pid=%s", DB_PROJECTS, $_GET["pid"]);
							$prows = $dbconn->query($qprows);
							check($dbconn, $prows, false);
							$prow = $prows->fetch_array();
							$groups = $prow["groups"];

							$qgrows = sprintf("SELECT pid, grp, uid FROM %s
									WHERE pid=%s", DB_GROUPS, $_GET["pid"]);
							$grows = $dbconn->query($qgrows);
							check($dbconn, $grows, false);

							$qurows = sprintf("SELECT uid, gid, name FROM %s",
									DB_USERS);
							$urows = $dbconn->query($qurows);
							check($dbconn, $urows, false);

							while ($grow = $grows->fetch_array())
								$users[$grow["uid"]] = $grow["grp"];

							while ($urow = $urows->fetch_array()) {
								if (array_key_exists($urow["uid"], $users)) {
									$group = $users[$urow["uid"]];

									echo("<tr>");
									echo("
										<td>
											<input
												type='checkbox'
												class='cb'
												name='uids[]'
												value='" . $urow["uid"] . "'>
										</td>
									");
									echo("<td>" . $urow["uid"] . "</td>");
									echo("<td>" . $urow["name"] . "</td>");
									echo("<td>");
									if ($urow["gid"] == GID_TEACHER &&
											$group == -1) {
										echo(GIDS[1]);
									} else {
										echo("
											<input
												type='hidden'
												name='students[]'
												value='" . $urow["uid"] . "'>
										");
										echo("<select name='groups[]'>");
										for ($i = -1; $i < $groups; $i++) {
											echo("
												<option
													value='" . (($i > $groups) ?
													-1 : $i) . "'" .
													(($i == $group ||
													($i == -1 &&
													$group >= $groups)) ?
													"selected" : "") . ">" .
													(($i == -1) ?
													"Geen" : $i + 1) .
												"</option>
											");
										}
										echo("</select>");
									}
									echo("</td></tr>");
								}
							}

							$grows->close();
							$dbconn->close();
						?>
					</table>
				</div>
			</form>
			<div class="modal" id="created" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button
								type="button"
								class="close"
								data-dismiss="modal">
								&times;
							</button>
							<h4 class="modal-title">Gebruikers toevoegen</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="groupadd.php">
								<table
									class="table table-striped tablesorter"
									id="userlist">
									<thead class="nopointer noselect">
										<tr>
											<th></th>
											<th>ID</th>
											<th>Naam</th>
											<th>Groep</th>
										</tr>
									</thead>
									<?php
										echo("
											<input
												type='hidden'
												name='pid'
												value='" . $_GET["pid"] . "'>
										");

										$dbconn = new mysqli(DB_URL . ":" .
										DB_PORT, DB_USER, DB_PASS, DB_NAME);
										check($dbconn, !$dbconn->connect_error,
												false);

										$qurows = sprintf(
												"SELECT uid, gid, name FROM %s", 
												DB_USERS);
										$urows = $dbconn->query($qurows);
										check($dbconn, $urows, false);

										while ($urow = $urows->fetch_array()) {
											if ($urow["gid"] != GID_ADMIN) {
												echo("<tr id='u" . $urow["uid"]
														. "'>");
												echo("
													<td>
														<input
															type='checkbox'
															class='cba'
															name='uids[]'
															value='" .
															$urow["uid"] . "'>
													</td>
												");
												echo("<td>" . $urow["uid"] .
														"</td>");
												echo("<td>" . $urow["name"] .
														"</td>");
												echo("<td>" . GIDS[$urow["gid"]]
														. "</td>");
												echo("</tr>");
											}
										}

										$urows->close();
										$dbconn->close();
									?>
								</table>
								<div class="form-group row">
									<div class="col-sm-offset-4 col-sm-8">
										<input
											type="submit"
											class="btn btn-primary"
											id="adddsubmit"
											value="Toevoegen" disabled>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
