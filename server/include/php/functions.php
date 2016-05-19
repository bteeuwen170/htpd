<?php
	function check($dbconn, $action, $output = true, $warn = false)
	{
		if ($action) {
			if ($output)
				echo("OK<br>");
			return true;
		} elseif ($warn) {
			if ($output)
				echo("<br>");
			return false;
		} else {
			$dbconn->close();
			if ($output)
				die("FAIL<br>");
			else
				exit(1); //TODO Same effect right?
		}
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
		if ($month > 7) //XXX This is right, right?
			return count(get_years()) - 1;
		else
			return count(get_years()) - 2;
	}

	function verify_login($gid) //TODO Run checks
	{
		$status = 200;

		$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
				DB_USER, DB_PASS, DB_NAME);

		if (isset($_COOKIE["session"])) {
			$login = "SELECT uid, gid, name, password FROM " .
					DB_USERS . " WHERE uid = '" . $_COOKIE["uid"] . "'";
			$result = $dbconn->query($login);

			$user = $result->fetch_assoc();

			if (hash(SESS_ENCRY,
					$_SERVER["HTTP_USER_AGENT"] . $user["password"]) ==
					$_COOKIE["session"])
				return true;
			else
				return false;

			/*if ($user["uid"] != $_COOKIE["uid"]) //TODO Should be 400?
				$status = 400;
			if ($user["gid"] != $_COOKIE["gid"])
				$status = 400;
			if ($user["firstname"] != $_COOKIE["firstname"])
				$status = 400;
			if ($user["lastname"] != $_COOKIE["lastname"])
				$status = 400;

			if ($user["gid"] > $gid)
				$status = 403;
			if (hash(SESS_ENCRY, $_SERVER["HTTP_USER_AGENT"] . $user["password"]) !=
					$_COOKIE["session"])
				return 403;*/
		} else {
			$status = 401;
			return false;
		}

		/*switch ($status) {
			case 400:
				header("HTTP/1.1 401 Unauthorized");
				header("Location: /include/html/400.html");
				break;
			case 401:
				header("HTTP/1.1 401 Unauthorized");
				header("Location: /user/logout.php");
				break;
			case 403:
				header("HTTP/1.0 403 Forbidden");
				header("Location: /include/html/403.html");
				break;
			default:
				return true;
		}*/

		$result->free();
		$dbconn->close();
	}
?>
