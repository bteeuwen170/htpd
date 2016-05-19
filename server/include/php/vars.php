<?php
	/* XXX These variables may only be modified before the inital setup! XXX */

	/* Algemeen */
	define("VERSION",		"1.0");
	define("URL_SITE",		"hydra.tnm");	/* Website URL */
	define("SESS_ENCRY",	"sha512");		/* Sessie encryptie */
	define("SESS_TIMEOUT",	180);			/* Sessie timout in dagen */

	/* GIDs */
	define("GIDS",			array("Administrator", "Leraar", "Leerling"));
	define("USR_ADMIN",	0);					/* Administrator */
	define("USR_TEACHER",	1);				/* Leraar */
	define("USR_STUDENT",	2);				/* Leerling */

	/* Database */
	define("DB_URL",		"localhost");	/* Database URL */
	define("DB_PORT",		"3306");		/* Database poort */
	define("DB_NAME",		"portfolios");	/* Database naam */
	define("DB_USER",		"root");		/* Database gebruiker */
	define("DB_PASS",		"my_password");	/* Database wachtwoord */

	define("DB_USERS",		"users");		/* Gebruikers tabel */
	define("DB_PROJECTS",	"projects");	/* Projecten tabel */
	define("DB_GROUPS",		"groups");		/* Project groepen tabel */

	/* Opslag */
	define("URL_STORAGE",	$_SERVER["DOCUMENT_ROOT"] . "/../storage/");
	define("URL_USERS",		URL_STORAGE . "students/");
	define("PRJ_NAMES",		array("Reflectie", "Feedback"));
	define("PRJ_FILES",		array("reflectie.html",
								  "feedback.html"));

	/* Overig */
	define("YR_FIRST",		2011);			/* Eerste jaar in database */

	/* Importeren parser */
	define("COL_USERNAME",	0);				/* Leerlingnummer */
	define("COL_FIRSTNAME",	1);				/* Roepnaam */
	define("COL_LASTNAME",	2);				/* Achternaam */
	define("COL_SUBJECTS",	3);				/* Vakken */
	define("TAR_SUBJECT",	"o&o");			/* Te filteren vak */

	/* XXX These variables may only be modified before the inital setup! XXX */
?>
