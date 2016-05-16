<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(USER_STUDENT))
		header("Location: /login.php");

	setcookie("session", null, -1, "/", URL_SITE);
	setcookie("uid", null, -1, "/", URL_SITE);
	setcookie("gid", null, -1, "/", URL_SITE);
	setcookie("firstname", null, -1, "/", URL_SITE);
	setcookie("lastname", null, -1, "/", URL_SITE);

	header("Location: /login.php");
?>
