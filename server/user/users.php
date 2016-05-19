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

				$("#editd").on("shown.bs.modal", function(e)
				{
					var row = document.getElementById("u" + rows[0]).children;
					$("#editduid").val(row[1].innerHTML);
					$("#editdname").val(row[2].innerHTML);
					$("#editdusername").val(row[3].innerHTML);
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
			<form method="post" action="userdel.php">
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
							title="Bewerken"
							data-toggle="modal"
							data-tooltip="true"
							data-placement="bottom"
							data-backdrop="static"
							data-target="#editd" disabled>
							<span class="glyphicon glyphicon-pencil"></span>
						</button>
						<?php
							if ($_COOKIE["gid"] == USR_ADMIN)
								echo("
									<button
										type='submit'
										class='btn btn-default btn-sm'
										id='delete'
										name='delete'
										title='Verwijderen'
										data-tooltip='true'
										data-placement='bottom' disabled>
										<span class='glyphicon glyphicon-trash'>
										</span>
									</button>
								");
						?>
					</div>
					<div class="btn-group">
						<?php
							if ($_COOKIE["gid"] == USR_ADMIN)
								echo("
									<button
										type='button'
										class='btn btn-default btn-sm'
										title='Importeren'
										data-toggle='modal'
										data-tooltip='true'
										data-placement='bottom'
										data-backdrop='static'
										data-target='#importd'>
										<span class='glyphicon
												glyphicon-import'></span>
									</button>
								");
						?>
						<button
							type="button"
							class="btn btn-default btn-sm"
							title="Exporteren"
							data-toggle="modal"
							data-tooltip="true"
							data-placement="bottom"
							data-backdrop="static"
							data-target="#exportd">
							<span class="glyphicon glyphicon-export"></span>
						</button>
					</div>
				</div>
				<div class="datacontainer">
					<table class="table table-striped sortable" id="userlist">
						<tr>
							<th><!--<input
									type="checkbox"
									id="sall"
									onclick="select_all(this)">-->
							</th>
							<th>ID</th>
							<th>Naam</th>
							<th>Gebruikersnaam</th>
							<th>Groep</th>
						</tr>
						<?php
							$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
							check($dbconn, !$dbconn->connect_error, false);

							$columns = sprintf(
									"SELECT uid, gid, name, username FROM %s", 
									DB_USERS);
							$rows = $dbconn->query($columns);
							check($dbconn, $rows, false);

							while ($row = $rows->fetch_array()) {
								echo("<tr id='u" . $row["uid"] . "'>");
								if ($row["gid"] == 0 ||
									$row["uid"] == $_COOKIE["uid"])
									echo("<td></td>");
								else
									echo("
										<td>
											<input type='checkbox' class='cb'
											name='cb[]' value='" . $row["uid"] .
											"' onclick='row_set(this)'>
										</td>
									");
								echo("<td>" . $row["uid"] . "</td>");
								echo("<td>" . $row["name"] . "</td>");
								echo("<td>" . $row["username"] . "</td>");
								echo("<td>" . GIDS[$row["gid"]] . "</td>");
								echo("</tr>");
							}

							$rows->free();
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
							<h4 class="modal-title">Gebruiker toevoegen</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="useradd.php">
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Groep
									</label>
									<div class="col-sm-8">
										<select
											name="gid"
											class="form-control">
											<option value="2">
												Leerling
											</option>
											<option value="1">
												Leraar
											</option>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Voornaam
									</label>
									<div class="col-sm-8">
										<input
											type="text"
											name="firstname"
											class="form-control"
											maxlength="32" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Achternaam
									</label>
									<div class="col-sm-8">
										<input
											type="text"
											name="lastname"
											class="form-control"
											maxlength="32" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Gebruikersnaam
									</label>
									<div class="col-sm-8">
										<input
											type="text"
											name="username"
											class="form-control"
											maxlength="64" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Wachtwoord
									</label>
									<div class="col-sm-8">
										<input
											type="password"
											name="password"
											class="form-control"
											maxlength="32" required>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-offset-4 col-sm-8">
										<input
											type="submit"
											class="btn btn-primary"
											value="Opslaan">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal" id="editd" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button
								type="button"
								class="close"
								data-dismiss="modal">
								&times;
							</button>
							<h4 class="modal-title">Gebruiker bewerken</h4>
						</div>
						<div class="modal-body">
							<form method="post" action="usermod.php">
								<input type="hidden" name="uid" id="editduid">
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Naam
									</label>
									<div class="col-sm-8">
										<input
											type="text"
											name="name"
											class="form-control"
											id="editdname"
											maxlength="64" required>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-4 form-control-label">
										Gebruikersnaam
									</label>
									<div class="col-sm-8">
										<input
											type="text"
											name="username"
											class="form-control"
											id="editdusername"
											maxlength="64" required>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-offset-4 col-sm-8">
										<input
											type="submit"
											class="btn btn-primary"
											value="Opslaan">
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal" id="importd" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button
								type="button"
								class="close"
								data-dismiss="modal">
								&times;
							</button>
							<h4 class="modal-title">Gebruikers importeren</h4>
						</div>
						<div class="modal-body">
							<p>
								Exporteer de volgende kolommen in de
								onderstaande volgorde naar een CSV bestand
								vanuit SOM:
								<ol>
									<li>Leerlingnummer</li>
									<li>Naam</li>
									<li>Vakken</li>
								</ol>
							</p>
							TODO
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
