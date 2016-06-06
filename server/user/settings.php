<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_STUDENT);
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="stylesheet" href="/include/lib/bootstrap/bootstrap.css">
		<link rel="stylesheet" href="/include/css/main.css">
		<link rel="stylesheet" href="/include/css/content.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/bootstrap.js"></script>

		<script type="text/javascript" src="/include/js/main.js"></script>
		<script type="text/javascript" src="/include/js/settings.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<form method="post" action="usermod.php">
			<input
				type="hidden"
				name="uid"
				value="<?php echo($_COOKIE["uid"]); ?>">
				<div id="optionbar">
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
				</div>
				<div class="datacontainer">
					<?php
						if ($_COOKIE["gid"] == GID_TEACHER)
							echo("
					<fieldset class='form-group'>
						<label>
							<input type='checkbox' name='prevrefl' disabled>
							Voorgaande reflectie weergeven in feedbackinterface
						</label>
					</fieldset>
							");
					?>
					<fieldset class="form-group">
						<label>Huidig wachtwoord</label>
						<input
							type="password"
							class="form-control"
							name="passwordold"
							placeholder="••••••••"
							onkeyup="newpass_show(this)" required>
					</fieldset>
					<div id="newpass">
						<fieldset class="form-group">
							<label>Nieuw wachtwoord</label>
							<input
								type="password"
								class="form-control"
								name="password"
								minlength="8"
								maxlength="255" required>
							<small class="text-muted">Minimaal 8 tekens</small>
						</fieldset>
						<fieldset class="form-group">
							<label>Nieuw wachtwoord herhalen</label>
							<input
								type="password"
								class="form-control"
								name="passwordrep"
								maxlength="255" required>
						</fieldset>
					</div>
				</div>
			</form>
		</div>
	</body>
</html>
