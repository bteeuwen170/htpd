<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	verify_login(GID_ADMIN, isset($_POST["submit"]));

	//TODO Checks and echo to user
	if ($_FILES["upload"]["error"] == UPLOAD_ERR_OK &&
			is_uploaded_file($_FILES["upload"]["tmp_name"])) {
		$ks =
			"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_";
		$students = array();
		$valid = 0;

		ob_start();

		$file = fopen($_FILES["upload"]["tmp_name"], "r");
		while (($row = fgetcsv($file, 0, IN_DEL, IN_ENC, IN_ESC)) != 0) {
			if (!$valid) { //Not the best way to check... Crap basically
				if ($row[COL_USERNAME] == COLN_USERNAME &&
						$row[COL_NAME] == COLN_NAME &&
						$row[COL_SUBJECTS] == COLN_SUBJECTS)
					$valid = 1;
				continue;
			}

			if (strpos($row[COL_SUBJECTS], TAR_SUBJECT) !== false) {
				$password = "";
				$max = mb_strlen($ks, "8bit") - 1;
				for ($i = 0; $i < 12; $i++) //XXX This is just utter crap
					$password .= $ks[random_int(0, $max)];

				$student = array();
				array_push($student, $row[COL_USERNAME]);
				array_push($student, $row[COL_NAME]);
				array_push($student, $password);
				array_push($students, $student);

				$post = array(
					"gid" => urlencode(GID_STUDENT),
					"name" => urlencode($row[COL_NAME]),
					"username" => urlencode("h" . $row[COL_USERNAME]),
					"password" => urlencode($password),
					"passwordrep" => urlencode($password)
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

		if (!$valid) {
			die("Ongeldig bestand");
		} else {
			ob_end_clean();

			header("Content-Disposition: attachment; filename=passwords.csv");
			header("Content-Type: application/octet-stream");

			echo(COLN_USERNAME . OUT_DEL .
					COLN_NAME . OUT_DEL .
					COLN_PASSWORDS . "\n");

			$out = fopen("php://output", "w");
			for ($i = 0; $i < count($students); $i++)
				fputcsv($out, $students[$i], OUT_DEL, OUT_ENC, OUT_ESC);
			fclose($out);
		}
	}

	//header("Location: " . $_SERVER["HTTP_REFERER"]); //What?
?>
