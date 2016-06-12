<?php
	/* XXX These variables may only be modified before the inital setup! XXX */

	/* Algemeen */
	define("VERSION",		"1.0");			/* Website version */
	define("URL_SITE",		"192.168.1.20");	/* Website URL */
	define("SESS_ENCRY",	"sha512");		/* Sessie encryptie */
	define("SESS_TIMEOUT",	180);			/* Sessie timout in dagen */

	/* GIDs */
	define("GIDS",			array("Administrator", "Leraar", "Leerling"));
											/* Gebruikers IDs */
	define("GID_ADMIN",		0);				/* Administrator */
	define("GID_TEACHER",	1);				/* Leraar */
	define("GID_STUDENT",	2);				/* Leerling */

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
											/* Pad naar opslag map */
	define("URL_USERS",		URL_STORAGE . "students/");
											/* Pad naar leerlingen map */
	define("MOTD_NAME",		"motd.html");	/* Naam motd bestand */
	define("CV_NAME",		"cv.html");		/* Naam CV bestand */
	define("PRJ_NAMES",		array("Productbeschrijving",
								  "Procesbeschrijving",
								  "Feedback"));
											/* Namen van project bestanden */
	define("PRJ_FILES",		array("productbeschrijving.html",
								  "procesbeschrijving.html",
								  "feedback.html"));
											/*
											 * Bestandnamen van project
											 * bestanden
											 */

	/* Document parser */
	define("HEADER_FIN",	"<!-- project: finished -->"); //TODO Change
											/*
											 * Header om ingeleverd document aan
											 * te duiden
											 */

	/* CSV parser */
	define("COL_USERNAME",	0);				/* Leerlingnummer (export en SOM) */
	define("COLN_USERNAME",	"Leerlingnummer");
	define("COL_NAME",		1);				/* Naam (export en SOM) */
	define("COLN_NAME",		"Naam");
	define("COL_SUBJECTS",	2);				/* Vakken (SOM) */
	define("COLN_SUBJECTS",	"Vakken");
	define("COL_PASSWORDS",	2);				/* Wachtwoord (export) */
	define("COLN_PASSWORDS","Wachtwoord");
	define("TAR_SUBJECT",	"o&o");			/* Te filteren vak */
	define("IN_DEL",		";");			/* SOM scheidingsteken */
	define("OUT_DEL",		",");			/* Export scheidingsteken */
	define("IN_ENC",		"\"");			/* SOM omheining */
	define("OUT_ENC",		"\"");			/* Export omheining */
	define("IN_ESC",		"\\");			/* SOM escape-teken */
	define("OUT_ESC",		"\\");			/* Export escape-teken */

	/* Overig */
	define("YR_FIRST",		2011);			/* Eerste jaar in database */

	/* XXX These variables may only be modified before the inital setup! XXX */
?>
