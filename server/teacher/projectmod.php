<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_TEACHER))
		header("Location: /user/logout.php");

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$pid = $_POST["pid"];
	$name = $_POST["name"];
	$groups = $_POST["groups"];
	$year = $_POST["year"];

	echo("Project wordt bewerkt... ");
	$project = "UPDATE " . DB_PROJECTS . " SET name='" . $name .
			"', groups='" . $groups . "', year='" . $year .
			"' WHERE pid='" . $pid . "'"; echo $project;
	check($dbconn, $dbconn->query($project));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
