<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /init/init.php");

	if (isset($_COOKIE["session"]))
		header("Location: /index.php");

	if (isset($_POST["username"])) {
		$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
		//TODO Check for more errors
		
		$username = $dbconn->real_escape_string($_POST["username"]);
		$password = $dbconn->real_escape_string($_POST["password"]);

		$login = sprintf("SELECT * FROM %s WHERE username='%s'",
				DB_USERS, $username);
		$result = $dbconn->query($login);

		if (!check($dbconn, $result->num_rows, false, true))
			$wup = true;

		if (!isset($wup)) {
			$row = $result->fetch_assoc();

			if (password_verify($password, $row["password"])) {
				if (isset($_POST["remember"]))
					$timeout = time() + 60 * 60 * 24 * SESS_TIMEOUT;
				else
					$timeout = false;

				setcookie("session",
						hash(SESS_ENCRY, $_SERVER["HTTP_USER_AGENT"] .
						$row["password"]), $timeout, "/", URL_SITE);
				setcookie("uid", $row["uid"],
						$timeout, "/", URL_SITE);
				setcookie("gid", $row["gid"],
						$timeout, "/", URL_SITE);
				setcookie("name", $row["name"],
						$timeout, "/", URL_SITE);

				header("Location: /index.php");
			} else {
				header("HTTP/1.1 401 Unauthorized");
				$wup = true;
			}
		}

		$result->close();
		$dbconn->close();
	}
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="icon" href="/include/img/navicon.png">
		<title>Helinium Technasium Portfolio Database</title>

		<link rel="stylesheet" type="text/css"
				href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/login.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/js/bootstrap.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<form id="signin" method="post" action="?">
				<center>
					<img
						id="logo"
						class="noselect"
						src="/include/img/logo.png"
						title="Helinium Technasium Portfolio Database"
						alt="Helinium Technasium Portfolio Database"
						width="227">
				</center>
				<?php
					if (isset($wup))
						echo("
							<div class='alert alert-danger alert-dismissible'
									role='alert'>
								<button type='button' class='close'
										data-dismiss='alert'>
									<span>
										&times;
									</span>
								</button>
								<div class='nopointer noselect'>
									Onjuiste gebruikersnaam of wachtwoord!
								</div>
							</div>");
				?> <!-- TODO Limits for text -->
				<p><input
						type="text"
						name="username"
						class="form-control"
						placeholder="Gebruikersnaam" autofocus required>
				</p>
				<p><input
						type="password"
						name="password"
						class="form-control"
						placeholder="Wachtwoord" required>
				</p>
				<p><input
						type="submit"
						name="login"
						class="btn btn-primary btn-lg btn-block"
						value="Inloggen">
				</p>
			</form>

			<?php
				include($_SERVER["DOCUMENT_ROOT"] . "/include/php/footer.php");
			?>
		</div>
	</body>
</html>
