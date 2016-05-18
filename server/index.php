<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /admin/init.php");

	if (!verify_login(USER_STUDENT))
		header("Location: /user/logout.php");
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="icon" href="/include/img/navicon.png">
		<title>Helinium Technasium Portfolio Database</title>

		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/index.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/js/sidebar.js"></script>
	</head>
	<body>
		<div class="wrapper">
			<header class="noselect">
				<div id="logo" class="header-item">
					<a
						onclick="set_sidebar(0)"
						href="/user/home.php"
						target="content">
						<img
							src="/include/img/logo.png"
							title="Helinium Technasium Portfolio Database"
							alt="Helinium Technasium Portfolio Database"
							height="20">
					</a>
				</div>
				<div id="logout" class="header-item">
					<a href="/user/logout.php">Uitloggen</a>
				</div>
				<div id="username" class="nopointer">
					<?php echo($_COOKIE["firstname"] . " " .
							$_COOKIE["lastname"]) ?>
				</div>
			</header>

			<div class="sidebar noselect">
				<ol class="sidebar-list">
					<?php
						if ($_COOKIE["gid"] < USER_STUDENT) {
							echo("<li><a href='/teacher/users.php'
									target='content'>Gebruikersbeheer
									</a></li>");
							echo("<li><a href='/teacher/projects.php'
									target='content'>Projectbeheer
									</a></li>");
						}

   						if ($_COOKIE["gid"] == USER_ADMIN) {
							//echo("<li><a href='file:////" . getcwd() .
							//		"../" . URL_STORAGE . PRJ_DIR . "
							//		target='content'>Bestandsbeheer (NYI)
							//		</a></li>");
							echo("<li><a href='/admin/motd.php'
									target='content'>Welkomstbericht
									</a></li>");
						} else {
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
									if ($_COOKIE["uid"] ==
											preg_replace("/\([^)]*\)/", "",
											$students[$i])) {
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
														class=
															'sidebar-item-label'
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

											for ($k = 0; $k < count(PRJ_FILES);
													$k++) {
												echo("
													<li
														id='" . $i . $j . $k . "
														'>
														<a
															onclick=\"
															set_sidebar($(this)
															.closest('li')
															.attr('id'))\" href=
															'/student/editor.php
															?path=" . $path .
															PRJ_FILES[$k] .
															"'target='content'>"
															. PRJ_NAMES[$k] . "
														</a>
													</li>
												");
											}
										} else { //FIXME Not very efficient...   Nor sorted alphabetically.... Seperate page maybe?
											$students = array();
											$tst = explode(",", $psi[$j][3]);
											for ($l = 0; $l < count($tst); $l++)
												if (substr_count($tst[$l], "(")) //FIXME Inconsistent and unreliable way of checking whether teacher or not
													array_push($students,
															preg_replace(
															"/\([^)]*\)/", "",
															$tst[$l]));
											for ($k = 0; $k < count(
													$students); $k++) {
												$path = $students[$k] . "/" .
														$psi[$j][1] . "/";

												echo("
													<li
														id='" . $i . $j . $k . "
														'>
														<a
															onclick=\"
															set_sidebar($(this)
															.closest('li')
															.attr('id'))\" href=
															'/teacher/editor.php
															?path=" . $path . "'
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
						}
					?>
				</ol>
			</div>

			<iframe
				id="content"
				name="content"
				src="/user/home.php"
				frameborder="0">
			</iframe>

			<footer class="nopointer noselect">
				<?php
					include($_SERVER["DOCUMENT_ROOT"] .
							"/include/php/footer.php");
				?>
			</footer>
		</div>
	</body>
</html>
