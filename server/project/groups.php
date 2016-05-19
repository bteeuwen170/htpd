<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USR_TEACHER))
		header("Location: /user/logout.php");
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" href="/include/css/main.css">
		<link rel="stylesheet" href="/include/css/content.css">
		<link rel="stylesheet" href="/include/css/manager.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript"
				src="/include/lib/sorttable/sorttable.js"></script>

		<script type="text/javascript">
			var rows = new Array();

			$(document).ready(function()
			{
				$("[data-tooltip='true']").tooltip({
					container: "body",
					trigger: "hover"
				});
			});

			function select_all(sa) //TODO NYI
			{
				var checkboxes = document.getElementsByClassName("cb");

				for (i = 0; i < checkboxes.length; i++)
					checkboxes[i].checked = sa.checked;

				document.getElementById("edit").disabled = true;
				document.getElementById("delete").disabled = false;
			}

			function row_set(row)
			{
				if (row.checked)
					rows.push(row.value);
				else
					rows.splice(rows.indexOf(row.value), 1);

				if (rows.length > 1) {
					document.getElementById("edit").disabled = true;
					document.getElementById("delete").disabled = false;
				} else if (rows.length > 0) {
					document.getElementById("edit").disabled = false;
					document.getElementById("delete").disabled = false;
				} else {
					document.getElementById("edit").disabled = true;
					document.getElementById("delete").disabled = true;
				}
			}
		</script>
	</head>
	<body>
		<div class="wrapper">
			<form method="post" action="groupadd.php">
				<div id="optionbar">
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
							type="button"
							class="btn btn-default btn-sm"
							id="edit"
							title="Wijzigen"
							data-tooltip="true"
							data-placement="bottom" disabled>
							<span class="glyphicon glyphicon-pencil"></span>
						</button>
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
					<table class="table table-striped sortable" id="grouplist">
						<tr>
							<th><!--<input
									type="checkbox"
									id="sall"
									onclick="select_all(this)">-->
							</th>
							<th>ID</th>
							<th>Voornaam</th>
							<th>Achternaam</th>
							<th>Groep</th>
						</tr>
						<?php
							$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
							check($dbconn, !$dbconn->connect_error, false);

							$columns = "SELECT groups, students FROM " .
									DB_PROJECTS . " WHERE pid=" . $_GET["pid"];
							$result = $dbconn->query($columns);
							check($dbconn, $result, false);
							$project = $result->fetch_assoc();

							$where = " WHERE";
							$students = explode(",", $project["students"]);
							for ($i = 0; $i < count($students); $i++) {
								$where = $where . " uid=" .
										preg_replace("/\([^)]*\)/", "",
										$students[$i]) . " OR";
							}
							$where = substr($where, 0, -3);

							$columns =
								"SELECT uid, gid, firstname, lastname FROM " .
										DB_USERS . $where;
							$rows = $dbconn->query($columns);
							check($dbconn, $rows, false);

							while ($row = $rows->fetch_array()) {
								echo("<tr>");
								echo("
									<td>
										<input type='checkbox' class='cb'
										name='cb[]' value='" . $row["uid"] .
										"' onclick='row_set(this)'>
									</td>
								");
								echo("<td>" . $row["uid"] . "</td>");
								echo("<td>" . $row["firstname"] . "</td>");
								echo("<td>" . $row["lastname"] . "</td>");
								echo("<td>");
								if ($row["gid"] == 2) {
									for ($i = 0; $i < count($students); $i++) {
										if ($row["uid"] ==
												preg_replace("/\([^)]*\)/", "",
												$students[$i])) {
											if (preg_match(
													"/(?<=\()(.+?)(?=\))/",
													$students[$i], $group))
												$group = $group[0] + 1;
											else
												$group = -1;
											break;
										}
									}

									echo("<select>");
									for ($i = -1;
											$i < $project["groups"]; $i++) {
										echo("<option value='" . $i . "'" .
												(($i == $group) ?
												" selected" : "") . ">" .
												(($i == -1) ? "Geen" : $i + 1) .
												"</option>");
									}
									echo("</select>");
								} else {
									echo("Leraar");
								}
								echo("</td></tr>");
							}

							$result->close();
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
							<h4 class="modal-title">Leerlingen toevoegen</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="groupadd.php">
								TODO Interface
								<div class="form-group row">
									<div class="col-sm-offset-4 col-sm-8">
										<input
											type="submit"
											class="btn btn-primary"
											value="Toevoegen">
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
