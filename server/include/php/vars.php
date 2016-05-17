<?php
	/* XXX These variables may only be modified before the inital setup! XXX */

	/* General */
	define("URL_SITE",		"hydra.tnm");
	define("SESS_ENCRY",	"sha512");
	define("SESS_TIMEOUT",	180);

	/* GIDs */
	define("GIDS",			array("Administrator", "Leraar", "Leerling"));
	define("USER_ADMIN",	0); //TODO To USR_*
	define("USER_TEACHER",	1);
	define("USER_STUDENT",	2);

	/* Database */
	define("DB_URL",		"localhost");
	define("DB_PORT",		"3306");
	define("DB_NAME",		"portfolios");
	define("DB_USER",		"root");
	define("DB_PASS",		"my_password");

	define("DB_USERS",		"users");
	define("DB_PROJECTS",	"projects");

	/* Storage */
	define("URL_STORAGE",	$_SERVER["DOCUMENT_ROOT"] . "/../storage/");
	define("URL_USERS",		URL_STORAGE . "students/");
	define("PRJ_NAMES",		array("Reflectie", "Feedback"));
	define("PRJ_FILES",		array("reflectie.html",
								  "feedback.html"));

	/* Other */
	define("YR_FIRST",		2011);

	/* Import parser */
	//define("TRG_SUBJECT",	"o&o");
?>
