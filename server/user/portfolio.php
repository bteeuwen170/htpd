<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_STUDENT);

	$path = URL_USERS . $_COOKIE["uid"] . "/";
	$files = scandir($path);

	$projects = array();

	for ($i = 2; $i < count($files); $i++) { //TODO Use same method that is used for index.php
		if ($files[$i] == CV_NAME)
			continue;

		echo("<h2>" . $files[$i] . "</h2>");

		for ($j = 0; $j < 2; $j++) {
			echo("<h4>" . PRJ_NAMES[$j] . "</h4>");

			$fpath = $path . $files[$i] . "/" . PRJ_FILES[$j];
			if (file_exists($fpath)) {
				$file = fopen($fpath, "r") or
					die("Er is een fout opgetreden!");

				if (filesize($fpath) > 0) {
					echo(fread($file, filesize($fpath)));
				}

				fclose($file);
			}
		}
	}

	//header("Location: " . $_SERVER["HTTP_REFERER"]); //What?
?>
