<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /init/init.php");

	if (!verify_login(USER_STUDENT))
		header("Location: /user/logout.php");
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="icon" href="/include/img/navicon.png">
		<title>Helinium Technasium Portfolio Database</title>

		<link rel="stylesheet" type="text/css"
				href="/include/lib/bootstrap/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/sidebar.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/js/bootstrap.js"></script>
		<script type="text/javascript"
				src="/include/js/sidebar.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top noselect">
			<div class="container">
				<div class="navbar-header">
					<a
						class="navbar-brand"
						href="/user/home.php"
						target="content">
						HTPD
					</a>
				</div>
				<ul class="nav navbar-nav">
					<li class="active">
						<a
							href="/user/home.php"
							target="content">
							Portfolio
						</a>
					</li>
					<?php
						if ($_COOKIE["gid"] < USER_STUDENT)
							echo("
								<li>
									<a
										href='/user/users.php'
										target='content'>
										Gebruikers
									</a>
								</li>
								<li>
									<a
										href='/project/projects.php'
										target='content'>
										Projecten
									</a>
								</li>
							");
						if ($_COOKIE["gid"] == USER_ADMIN)
							echo("
								<li>
									<a
										href='/editor/motd.php'
										target='content'>
										Welkomstbericht
									</a>
								</li>
							");
					?>
					<li>
						<a
							href="/user/settings.php"
							target="content">
							Instellingen
						</a>
					</li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<p class="navbar-text nopointer">
							<?php echo($_COOKIE["firstname"] . " " .
									$_COOKIE["lastname"]) ?>
						</p>
					</li>
					<li>
						<a href="/user/logout.php">
							Uitloggen
						</a>
					</li>
				</ul>
			</div>
		</nav>
		<div class="wrapper">
			<?php
				if ($_COOKIE["gid"] != USER_ADMIN) {
					echo("<div class='sidebar noselect'>");
					echo("<ol class='sidebar-list'>");

					$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
					check($dbconn, !$dbconn->connect_error, false);

					$qutable = sprintf(
							"SELECT pid, name, students, year FROM %s",
							DB_PROJECTS);
					$prows = $dbconn->query($qutable);
					check($dbconn, $prows, false);

					$psi = array();
					$teacher = ($_COOKIE["gid"] == USER_TEACHER);

					while ($prow = $prows->fetch_array()) {
						$students = explode(",", $prow["students"]);
						for ($i = 0; $i < count($students); $i++)
							if ($_COOKIE["uid"] == preg_replace("/\([^)]*\)/",
									"", $students[$i])) {
								$pi = array();
								array_push($pi, $prow["year"]);
								array_push($pi, $prow["pid"]);
								array_push($pi, $prow["name"]);
								if ($teacher)
									array_push($pi, $prow["students"]);
								array_push($psi, $pi);
							}
					}

					$prows->close();
					$dbconn->close();

					$years = get_years();
					for ($i = 0; $i < count($years); $i++) {
						$c = false;
						for ($j = 0; $j < count($psi); $j++) {
							if ($psi[$j][0] == $i) {
								if (!$c) {
									echo("
										<li>
											<label
												class='sidebar-item-label'
												for='y" . $i . "'>"
												. $years[$i] . "
											</label>
											<input
												type='checkbox'
												id='y" . $i . "'/>
											<ol class='sidebar-item0'>
									");
									$c = true;
								}

								echo("
									<li>
										<label
											class='sidebar-item-label'
											for='p" . $j . "'>"
											. $psi[$j][2] . "
										</label>
										<input
											type='checkbox'
											id='p" . $j . "'/>
										<ol class='sidebar-item1'>
								");

   								if ($_COOKIE["gid"] == USER_STUDENT) {
									$path = $_COOKIE["uid"] . "/" .
											$psi[$j][1] . "/";

									for ($k = 0; $k < count(PRJ_FILES); $k++) {
										echo("
											<li
												id='" . $i . $j . $k . "'>
												<a
													onclick=\"set_sidebar(
													$(this).closest('li')
													.attr('id'))\" href=
													'/editor/student.php?path="
													. $path . PRJ_FILES[$k] .
													"'target='content'>"
													. PRJ_NAMES[$k] . "
												</a>
											</li>
										");
									}
								} else { //FIXME Not very efficient...
									$students = array();
									$tst = explode(",", $psi[$j][3]);
									for ($l = 0; $l < count($tst); $l++)
										if (substr_count($tst[$l], "(")) //FIXME Inconsistent and unreliable way of checking whether teacher or not
											array_push($students,
													preg_replace("/\([^)]*\)/",
													"", $tst[$l]));
									for ($k = 0; $k < count($students); $k++) {
										$path = $students[$k] . "/" .
												$psi[$j][1] . "/";

										echo("
											<li
												id='" . $i . $j . $k . "'>
												<a
													onclick=\"set_sidebar(
													$(this).closest('li')
													.attr('id'))\" href=
													'/editor/teacher.php?path="
													. $path . "'
													target='content'>" .
													$students[$k] . "
												</a>
											</li>
										");
									}
								}
								echo("</ol></li>");
							}
						}
						if ($c)
							echo("</ol></li>");
					}
					echo("</ol></div>");
				}
			?>

			<iframe
				id="content"
				name="content"
				src="/user/home.php"
				frameborder="0">
			</iframe>

			<?php
				include($_SERVER["DOCUMENT_ROOT"] .
						"/include/php/footer.php");
			?>
		</div>
	</body>
</html>
