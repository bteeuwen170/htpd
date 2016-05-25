<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(GID_TEACHER))
		header("Location: /user/logout.php");
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" type="text/css"
				href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/content.css">
		<link rel="stylesheet" type="text/css" href="/include/css/manager.css">

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
					/*$projects = array();
					$teacher = ($_COOKIE["gid"] == GID_TEACHER);
					$users = array();
					$years = get_years();

					$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
					check($dbconn, !$dbconn->connect_error, false);

					$qptable =
						sprintf("SELECT pid, name, year FROM %s", DB_PROJECTS);
					$ptable = $dbconn->query($qptable);
					check($dbconn, $ptable, false);

					while ($prow = $ptable->fetch_array()) {					 //XXX This can't be efficient right?
						$groups = array();
						$hasuser = false;
						$qgtable =
							sprintf("SELECT pid, grp, uid FROM %s WHERE pid=%s",
									DB_GROUPS, $prow["pid"]);
						$gtable = $dbconn->query($qgtable);
						check($dbconn, $gtable, false);
						while ($grow = $gtable->fetch_array()) {
							array_push($groups, $grow);
							if ($grow["uid"] == $_COOKIE["uid"])
								$hasuser = true;
						}
						$gtable->close();

						if (!$hasuser)
							continue;

						$project = array();
						array_push($project, $prow["pid"]);
						array_push($project, $prow["name"]);
						array_push($project, $prow["year"]);
						array_push($project, $groups);
						array_push($projects, $project);
					}

					if ($_COOKIE["gid"] == GID_TEACHER) {
						$qutable =
							sprintf("SELECT name FROM %s", DB_USERS);
						$utable = $dbconn->query($qutable);
						check($dbconn, $utable, false);

						//TODO

						$utable->close();
					} else {
						unset($users);
					}

					$ptable->close();
					$dbconn->close();

					echo("<div class='sidebar noselect'>");
					echo("<ol class='sidebar-list'>");

					for ($i = 0; $i < count($years); $i++) {
						$c = false;
						for ($j = 0; $j < count($projects); $j++) {
							if ($projects[$j][2] == $i) {
								if (!$c) {
									echo("
										<li>
											<label
												class='sidebar-item-label'
												for='y" . $i . "'>"
												. $years[$i] . "
											</label>
											<input
												type='checkbox'
												id='y" . $i . "'/>
											<ol class='sidebar-item0'>
									");
									$c = true;
								}

								echo("
									<li>
										<label
											class='sidebar-item-label'
											for='p" . $j . "'>"
											. $projects[$j][1] . "
										</label>
										<input
											type='checkbox'
											id='p" . $j . "'/>
										<ol class='sidebar-item1'>
								");

   								if ($_COOKIE["gid"] == GID_STUDENT) {
									$path = $_COOKIE["uid"] . "/" .
											$projects[$j][0] . "/";

									for ($k = 0; $k < count(PRJ_FILES); $k++) {
										echo("
											<li
												id='" . $i . $j . $k . "'>
												<a
													onclick=\"sidebar_set(
													$(this).closest('li')
													.attr('id'))\" href=
													'/editor/student.php?path="
													. $path . PRJ_FILES[$k] .
													"'target='content'>"
													. PRJ_NAMES[$k] . "
												</a>
											</li>
										");
									}
								} else {
									for ($k = 0; $k < count($projects[$j][3]);
											$k++) {
										if ($projects[$j][3][$k][2] !=
												$_COOKIE["uid"]) {
											$path = $projects[$j][3][$k][2] . "/" .
													$projects[$j][0] . "/";

											echo("
												<li
													id='" . $i . $j . $k . "'>
													<a
														onclick=\"sidebar_set(
														$(this).closest('li')
														.attr('id'))\" href=
														'/editor/teacher.php?path="
														. $path . "'
														target='content'>" .
														$projects[$j][3][$k][2] . "
													</a>
												</li>
											");
										}
									}*/
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
