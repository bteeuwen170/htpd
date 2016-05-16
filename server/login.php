<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /admin/init.php");

	verify_login(USER_STUDENT);

	if (isset($_POST["username"])) {
		$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
				DB_USER, DB_PASS, DB_NAME);
		//TODO Check for more errors

		$login = "SELECT * FROM " . DB_USERS ." WHERE username = '" .
				$_POST["username"] . "'";
		$result = $dbconn->query($login);

		if (!check($dbconn, $result->num_rows, false, true)) {
			$login = "SELECT * FROM " . DB_USERS ." WHERE email = '" .
					$_POST["username"] . "'";
			$result = $dbconn->query($login);

			if (!check($dbconn, $result->num_rows, false, true))
				$wup = true;
		}

		if (!isset($wup)) {
			$row = $result->fetch_assoc();

			if (password_verify($_POST["password"], $row["password"])) {
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
				setcookie("firstname", $row["firstname"],
						$timeout, "/", URL_SITE);
				setcookie("lastname", $row["lastname"],
						$timeout, "/", URL_SITE);

				header("Location: /index.php");
			} else {
				$wup = true;
			}
		}

		$result->free();
		$dbconn->close();
	}
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="icon" href="/include/img/navicon.png">
		<title>Helinium Technasium Portfolio's</title>

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
						src="/include/img/logo-dark.png"
						title="Helinium Technasium"
						alt="Helinium Technasium"
						width="300">
				</center>
				<?php
					if (isset($wup)) //TODO Fancier
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
				?>
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

			<footer class="nopointer noselect">
				<?php
					include($_SERVER["DOCUMENT_ROOT"] .
							"/include/php/footer.php");
				?>
			</footer>
		</div>
	</body>
</html>
