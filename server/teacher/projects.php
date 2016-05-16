<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_TEACHER))
		header("Location: /user/logout.php");
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/lib/bootstrap/css/bootstrap.css">
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

				$(".pr").click(function()
				{
					location.href = "groups.php?pid=" +
							$(this).closest("tr").attr("data-pid");
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
			<form method="post" action="projectdel.php">
				<div id="optionbar">
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
					<div class="btn-group">
						<button
							type="button"
							class="btn btn-default btn-sm"
							id="edit"
							title="Bewerken"
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
					<div class="input-group input-group-sm" id="years">
						<span class="input-group-addon">Jaar</span>
						<select class="form-control">
							<?php
								$years = get_years();
								for ($i = 0; $i < count($years); $i++) {
									echo("
										<option
											value='y" . $i . "' " .
											(($i == get_year()) ?
											"selected>" : ">") .
											$years[$i] . "
										</option>
									");
								}
							?>
						</select>
					</div>
				</div>
				<div class="datacontainer">
					<table
						class="table table-striped sortable"
						id="projectlist">
						<tr>
							<th><!--<input
									type="checkbox"
									id="sall"
									onclick="select_all(this)">-->
							</th>
							<th>ID</th>
							<th>Projectnaam</th>
							<th>Groepen</th>
							<th>Schooljaar (TODO: hide)</th>
							<th>Actief</th>
						</tr>
						<?php
							$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
							//TODO Check for errors

							$columns =
								"SELECT pid, name, groups, year, active FROM "
										. DB_PROJECTS;
							$rows = $dbconn->query($columns);
							check($dbconn, $rows, false);

							while ($row = $rows->fetch_array()) {
								echo("<tr data-pid='" . $row["pid"] . "'>");
								echo("
									<td>
										<input type='checkbox' class='cb'
										name='cb[]' value='" . $row["pid"] .
										"' onclick='row_set(this)'>
									</td>
								");
								echo("<td class='pr'>" .
										$row["pid"] . "</td>");
								echo("<td class='pr'>" .
										$row["name"] . "</td>");
								echo("<td class='pr'>" .
										$row["groups"] . "</td>");
								echo("<td class='pr'>" .
										get_years()[$row["year"]] . "</td>");
								echo("
									<td>
										<input
											type='checkbox'
											" . ($row["active"] ?
											"checked" : "") . " disabled>
									</td>
								"); //TODO Shouldn't be a checkbox
								echo("</tr>");
							}

							$rows->free();
							$dbconn->close();
						?>
					</table>
				</form>
			</div>
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
							<h4 class="modal-title">Project toevoegen</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="projectadd.php">
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Projectnaam
									</label>
									<div class="col-sm-8">
										<input
											type="text"
											name="name"
											class="form-control"
											maxlength="64" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Groepen
									</label>
									<div class="col-sm-8">
										<input
											type="number"
											name="groups"
											class="form-control"
											min="1" max="255"
											value="1" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Schooljaar
									</label>
									<div class="col-sm-8">
										<select
											name="year"
											class="form-control">
											<?php //XXX Deja Vu!
												$years = get_years();
												for ($i = 0; $i < count($years);
														$i++) {
													echo("
														<option
															value='" . $i . "' "
															. (($i == get_year()
															) ? "selected>" : ">
															") . $years[$i] . "
														</option>
													");
												}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Actief
									</label>
									<div class="col-sm-8">
										<input
											type="checkbox"
											name="active">
									</div>
								</div>
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
