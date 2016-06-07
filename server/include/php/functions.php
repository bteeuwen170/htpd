<?php
	function check($dbconn, $action, $output = true, $warn = false)
	{
		if ($action) {
			if ($output)
				echo("OK<br>");
			return true;
		} else if ($warn) {
			if ($output)
				echo("WARN<br>");
			return false;
		} else {
			$dbconn->close();
			if ($output)
				die("FAIL<br>");
			else
				exit(1);
		}
	}

	function deldir($j) {
		foreach(scandir($j) as $k) {
			if ("." === $k || ".." === $k)
				continue;
			else if (is_dir("$j/$k"))
				deldir("$j/$k");
			else
				unlink("$j/$k");
		}

		rmdir($j);
	}

	function get_years()
	{
		$year = date("Y");
		$years = array();

		for ($i = YR_FIRST; $i <= $year;)
			array_push($years, $i . " - " . ++$i);

		return $years;
	}

	function get_year()
	{
		$month = date("m");
		if ($month > 7)
			return count(get_years()) - 1;
		else
			return count(get_years()) - 2;
	}

	function verify_login($gid) //TODO Run more checks
	{
		if (isset($_COOKIE["session"])) {
			$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
					DB_USER, DB_PASS, DB_NAME);

			$qurow = sprintf("SELECT uid, gid, name, password
					FROM %s WHERE uid=%s", DB_USERS, $_COOKIE["uid"]);
			$urow = $dbconn->query($qurow);
			$user = $urow->fetch_assoc();

			if (hash(SESS_ENCRY,
					$_SERVER["HTTP_USER_AGENT"] . $user["password"]) !=
					$_COOKIE["session"]) {
				header("HTTP/1.1 401 Unauthorized");
				header("Location: /user/logout.php"); //TODO Sure?
			}

			if ($_COOKIE["uid"] != $user["uid"] ||
				$_COOKIE["gid"] != $user["gid"] ||
				$_COOKIE["name"] != $user["name"] ||
				$gid < $user["gid"]) {
				header("HTTP/1.0 403 Forbidden");
				header("Location: /include/html/403.html");
			}

			$urow->close();
			$dbconn->close();
		} else {
			header("HTTP/1.1 401 Unauthorized");
			header("Location: /login.php");
		}

		return true;
	}
?>
