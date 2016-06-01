<!DOCTYPE html>

<?php
	include($_SERVER["DOCUMENT_ROOT"] . "/include/php/include.php");

	if (!file_exists(URL_STORAGE . "configured"))
		header("Location: /init/init.php");

	verify_login(GID_STUDENT);
?>

<html>
	<head>
		<meta charset="UTF-8">

		<link rel="icon" href="/include/img/navicon.png">
		<title>Helinium Technasium Portfolio Database</title>

		<link rel="stylesheet" type="text/css"
				href="/include/lib/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/sidebar.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="/include/js/sidebar.js"></script>

		<script type="text/javascript">
			var admin = <?php echo(($_COOKIE["gid"] == GID_ADMIN) ? 1 : 0); ?>;

			$(document).ready(function()
			{
				if (admin)
					sidebar_hide(admin);
			});
		</script>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-static-top noselect">
			<div class="container">
				<ul class="nav navbar-nav">
					<li class="active">
						<a
							href="/user/home.php"
							onclick="sidebar_show(admin)"
							target="content">
						<?php
								if ($_COOKIE["gid"] == GID_ADMIN)
									echo("Hoofdpagina");
								else if ($_COOKIE["gid"] == GID_TEACHER)
									echo("Feedback");
								else
									echo("Portfolio");
							?>
						</a>
					</li>
					<?php
						if ($_COOKIE["gid"] < GID_STUDENT)
							echo("
								<li>
									<a
										href='/project/projects.php'
										target='content'
										onclick='sidebar_hide(admin)'>
										Projectbeheer
									</a>
								</li>
							");
						if ($_COOKIE["gid"] == GID_ADMIN)
							echo("
								<li>
									<a
										href='/user/users.php'
										target='content'
										onclick='sidebar_hide(admin)'>
										Gebruikersbeheer
									</a>
								</li>
								<li>
									<a
										href='/editor/motd.php'
										target='content'
										onclick='sidebar_hide(admin)'>
										Welkomstbericht
									</a>
								</li>
							");
						echo("
							<li>
								<a
									href='/user/settings.php'
									target='content'
									onclick='sidebar_hide(admin)'>
									Instellingen
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
					$years = get_years();

					$dbconn = new mysqli(DB_URL . ":" . DB_PORT,
							DB_USER, DB_PASS, DB_NAME);
					check($dbconn, !$dbconn->connect_error, false);

					$qptable = sprintf("SELECT pid, name, groups, year FROM %s",
							DB_PROJECTS);
					$ptable = $dbconn->query($qptable);
					check($dbconn, $ptable, false);

					while ($prow = $ptable->fetch_array()) {					 //XXX This is crap
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
						array_push($project, $prow["groups"]);
						array_push($project, $prow["year"]);
						array_push($project, $groups);
						array_push($projects, $project);
					}

					$ptable->close();
					$dbconn->close();

					echo("
						<div class='sidebar noselect'>
							<ol class='sidebar-list'>
								<li>
									<a
										href='/user/home.php'
										target='content'>
										Hoofdpagina
									</a>
								</li>
					");

					for ($i = 0; $i < count($years); $i++) {
						$c = false;
						for ($j = 0; $j < count($projects); $j++) {
							if ($projects[$j][3] == $i) {
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
									for ($k = 0; $k < count(PRJ_FILES); $k++) {
										echo("
											<li
												id='" . $i * 200 . $j . $k . "'>
												<a
													onclick=\"sidebar_set(
													$(this).closest('li')
													.attr('id'))\"
													href='
													/editor/student.php?pid=" .
													$projects[$j][0] . "&file="
													. $k .
													"' target='content'>"
													. PRJ_NAMES[$k] . "
												</a>
											</li>
										");
									}
								} else {
									for ($k = 0; $k < $projects[$j][2]; $k++) {
										echo("
											<li
												id='" . $i * 200 . $j . $k . "'>
												<a
													onclick=\"sidebar_set(
													$(this).closest('li')
													.attr('id'))\"
													href='
													/project/users.php?pid=" .
													$projects[$j][0] . "&grp=" .
													$k . "' target='content'>
													Groep " . ($k + 1) . "
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
				include($_SERVER["DOCUMENT_ROOT"] . "/include/php/footer.php");
			?>
		</div>
	</body>
</html>
