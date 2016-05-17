<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$uid = $bdconn->real_escape_string($_POST["uid"]);
	$firstname = $dbconn->real_escape_string($_POST["firstname"]);
	$lastname = $dbconn->real_escape_string($_POST["lastname"]);
	$username = $dbconn->real_escape_string($_POST["username"]);
	$email = $dbconn->real_escape_string($_POST["email"]);

	/*echo("Controleren op gebruikersnaam beschikbaarheid... ");
	$usernamecheck = "SELECT username FROM " . DB_USERS . " WHERE username = '"
			. $username . "'";
	check($dbconn, mysqli_num_rows($dbconn->query($usernamecheck)) == 0);
	//TODO Fix this, lazy f*ck!
	echo("Controleren op emailadres beschikbaarheid... ");
	$emailcheck = "SELECT email FROM " . DB_USERS . " WHERE email = '" .
			$email . "'";
	check($dbconn, mysqli_num_rows($dbconn->query($emailcheck)) == 0);*/

	echo("Gebruiker wordt aangepast... ");
	$user = "UPDATE " . DB_USERS . " SET firstname='" . $firstname .
			"', lastname='" . $lastname . "', username='" . $username .
			"', email='" . $email . "' WHERE uid='" . $uid . "'";
	check($dbconn, $dbconn->query($user));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
