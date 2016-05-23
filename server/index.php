<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /init/init.php");

	if (!verify_login(GID_STUDENT))
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
		<script type="text/javascript" src="/include/js/sidebar.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top noselect">
			<div class="container">
				<div class="navbar-header">
					<a
						class="navbar-brand nopointer">
						<img
							id="logo"
							class="noselect"
							src="/include/img/logo.png"
							title="Helinium Technasium Portfolio Database"
							alt="Helinium Technasium Portfolio Database"
							width="40">
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
						if ($_COOKIE["gid"] < GID_STUDENT)
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
						else
							echo("
								<li>
									<a
										href='/user/settings.php'
										target='content'>
										Instellingen
									</a>
								</li>
							");
						if ($_COOKIE["gid"] == GID_ADMIN)
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
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li>
						<p class="navbar-text nopointer">
							<?php echo($_COOKIE["name"]) ?>
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
				if ($_COOKIE["gid"] != GID_ADMIN) {
					$projects = array();
					$teacher = ($_COOKIE["gid"] == GID_TEACHER);
					$users = array();
					$years = get_years();

					echo("<div class='sidebar noselect'>");
					echo("<ol class='sidebar-list'>");

					$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
					check($dbconn, !$dbconn->connect_error, false);

					$qptable =
						sprintf("SELECT pid, name, year FROM %s", DB_PROJECTS);
					$ptable = $dbconn->query($qptable);
					check($dbconn, $ptable, false);

					while ($prow = $ptable->fetch_array()) {					 //XXX This can't be efficient right?
						$groups = array();
						$hasuser = false;
						$qgtable =
							sprintf("SELECT pid, grp, uid FROM %s WHERE pid=%s",
									DB_GROUPS, $prow["pid"]);
						$gtable = $dbconn->query($qgtable);
						check($dbconn, $gtable, false);
						while ($grow = $gtable->fetch_array()) {
							array_push($groups, $grow);
							if ($grow["uid"] == $_COOKIE["uid"])
								$hasuser = true;
						}
						$gtable->close();

						if (!$hasuser)
							continue;

						$project = array();
						array_push($project, $prow["pid"]);
						array_push($project, $prow["name"]);
						array_push($project, $prow["year"]);
						array_push($project, $groups);
						array_push($projects, $project);
					}

					if ($_COOKIE["gid"] == GID_TEACHER) {
						$qutable =
							sprintf("SELECT name FROM %s", DB_USERS);
						$utable = $dbconn->query($qutable);
						check($dbconn, $utable, false);

						//TODO

						$utable->close();
					} else {
						unset($users);
					}

					$ptable->close();
					$dbconn->close();

					for ($i = 0; $i < count($years); $i++) {
						$c = false;
						for ($j = 0; $j < count($projects); $j++) {
							if ($projects[$j][2] == $i) {
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
											. $projects[$j][1] . "
										</label>
										<input
											type='checkbox'
											id='p" . $j . "'/>
										<ol class='sidebar-item1'>
								");

   								if ($_COOKIE["gid"] == GID_STUDENT) {
									$path = $_COOKIE["uid"] . "/" .
											$projects[$j][0] . "/";

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
								} else {
									for ($k = 0; $k < count($projects[$j][3]);
											$k++) {
										if ($projects[$j][3][$k][2] !=
												$_COOKIE["uid"]) {
											$path = $projects[$j][3][$k][2] . "/" .
													$projects[$j][0] . "/";

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
														$projects[$j][3][$k][2] . "
													</a>
												</li>
											");
										}
									}

									/*$students = array();
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
									}*/
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
