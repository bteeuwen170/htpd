<!DOCTYPE html>

<html>
	<head>
		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/html-docx-js/html-docx.js"></script>
		<script type="text/javascript"
				src="/include/lib/filesaver/filesaver.js"></script>

		<script type="text/javascript"
				src="/include/js/portfolio.js"></script>
	</head>
	<body>
		<?php
			include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

			verify_login(GID_STUDENT);

			$dbconn = new mysqli(DB_URL . ":" . DB_PORT, DB_USER, DB_PASS, DB_NAME);
			check($dbconn, !$dbconn->connect_error, false);

			$path = URL_USERS . $_COOKIE["uid"] . "/";

			$years = get_years();
			$projects = array();
			$files = scandir($path);

			$qptable = sprintf("SELECT pid, name, year FROM %s", DB_PROJECTS);
			$ptable = $dbconn->query($qptable);
			check($dbconn, $ptable, false);

			while ($prow = $ptable->fetch_array()) { //XXX This is crap
				$qgtable = sprintf("SELECT pid, grp, uid FROM %s WHERE pid=%s",
						DB_GROUPS, $prow["pid"]);
				$gtable = $dbconn->query($qgtable);
				check($dbconn, $gtable, false);
				while ($grow = $gtable->fetch_array())
					if ($grow["uid"] == $_COOKIE["uid"]) {
						array_push($projects, $prow);
						break;
					}
				$gtable->close();
			}

			$cvpath = $path . CV_NAME;
			if (file_exists($cvpath)) {
				echo("<h2>Curriculum Vitae</h2>");

				$file = fopen($cvpath, "r") or
					die("Er is een fout opgetreden!");

				if (filesize($cvpath) > 0)
					echo(fread($file, filesize($cvpath)));

				fclose($file);

				echo("<br style='page-break-before: always; clear: both;' />");
			}

			for ($i = 0; $i < count($years); $i++) {
				for ($j = 0; $j < count($projects); $j++) {
					if ($projects[$j][2] == $i) {
						echo("<h2>" . $projects[$j][1] . " (" .
								$years[$projects[$j][2]]  . ")</h2>");

						for ($k = 0; $k < 2; $k++) {
							$fpath = $path . $projects[$j][0] . "/" . PRJ_FILES[$k];
							if (file_exists($fpath)) {
								echo("<h4>" . PRJ_NAMES[$k] . "</h4>");

								$file = fopen($fpath, "r") or
									die("Er is een fout opgetreden!");

								if (filesize($fpath) > 0)
									echo(fread($file, filesize($fpath)));

								fclose($file);
							}
						}

						echo("<br style='page-break-before: always; clear: both;' />");
					}
				}
			}
		?>
	</body>
</html>
