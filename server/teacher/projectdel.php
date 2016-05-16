<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$pids = $_POST["cb"];

	for ($i = 0; $i < count($pids); $i++) {
		echo("Project informatie wordt opgehaald... ");
		$columns = "SELECT pid FROM " . DB_PROJECTS . " WHERE pid = '" .
				$pids[$i] . "'";
		$result = $dbconn->query($columns);
		check($dbconn, $result->num_rows);
		$row = $result->fetch_assoc();

		echo("Project wordt verwijderd... ");
		$project =
			"DELETE FROM " . DB_PROJECTS . " WHERE pid='" . $pids[$i] . "'";
		check($dbconn, $dbconn->query($project));

		//TODO Option to delete files as well, maybe only as admin?

		$result->free();
	}

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
