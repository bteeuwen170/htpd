<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_TEACHER);

	echo("Verbinding maken met SQL database... ");
	$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
	check($dbconn, !$dbconn->connect_error);

	$name = $dbconn->real_escape_string($_POST["name"]);
	$groups = $dbconn->real_escape_string($_POST["groups"]);
	$year = $dbconn->real_escape_string($_POST["year"]);

	echo("Project wordt aangemaakt... ");
	$project = sprintf("INSERT INTO %s (name, groups, year)
		VALUES('%s', %s, %s)", DB_PROJECTS, $name, $groups, $year);
	check($dbconn, $dbconn->query($project));

	echo("PID wordt opgehaald... ");
	$qprow = sprintf("SELECT pid FROM %s ORDER BY pid DESC LIMIT 1",
			DB_PROJECTS);
	$prow = $dbconn->query($qprow);
	check($dbconn, $prow);
	$pid = ($prow->fetch_array())["pid"];

	echo("Gebruiker wordt aan project toegevoegd... ");
	$user = sprintf("INSERT INTO %s (pid, grp, uid) VALUES(%s, -1, %s)",
		DB_GROUPS, $pid, $_COOKIE["uid"]);
	check($dbconn, $dbconn->query($user));

	$dbconn->close();

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
