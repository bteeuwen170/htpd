<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!verify_login(GID_ADMIN))
		header("Location: /user/logout.php");

	if (!isset($_POST["submit"]))
		header("Location: /index.php");

	//TODO Checks and echo to user
	if ($_FILES["upload"]["error"] == UPLOAD_ERR_OK &&
			is_uploaded_file($_FILES["upload"]["tmp_name"])) {
		$valid = 0;
		$file = fopen($_FILES["upload"]["tmp_name"], "r");
		while (($row = fgetcsv($file, 0, ";", "\"", "\\")) != 0) {
			if (!$valid) { //Not the best way to check...
				if ($row[COL_USERNAME] == COLN_USERNAME &&
						$row[COL_NAME] == COLN_NAME &&
						$row[COL_SUBJECTS] == COLN_SUBJECTS)
					$valid = 1;
				continue;
			}

			if (strpos($row[COL_SUBJECTS], TAR_SUBJECT) != 0) {
				$post = array(
					"gid" => urlencode(GID_STUDENT),
					"name" => urlencode($row[COL_NAME]),
					"username" => urlencode("h" . $row[COL_USERNAME]),
					"password" => urlencode("password"), //TODO Gen random pass
				);

				$fieldsstr = null;
				foreach ($post as $key => $value)
					$fieldsstr .= $key . "=" . $value . "&";
				$fieldsstr = rtrim($fieldsstr, "&");
				$curl = curl_init($_SERVER["HTTP_HOST"] . "/user/useradd.php");
				curl_setopt($curl, CURLOPT_POST, count($post));
				curl_setopt($curl, CURLOPT_POSTFIELDS, $fieldsstr);
				$res = curl_exec($curl);
				curl_close($curl);
			}
		}

		if (!$valid)
			echo("Ongeldig bestand");
	}

	header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
