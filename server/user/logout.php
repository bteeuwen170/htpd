<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(GID_STUDENT))
		header("Location: /login.php");

	setcookie("session", null, -1, "/", URL_SITE);
	setcookie("uid", null, -1, "/", URL_SITE);
	setcookie("gid", null, -1, "/", URL_SITE);
	setcookie("name", null, -1, "/", URL_SITE);

	header("Location: /login.php");
?>
