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

		<link rel="icon" href="/include/img/favicon.png">
		<title>Helinium Technasium Portfolio Database</title>

		<link rel="stylesheet" type="text/css"
				href="/include/lib/bootstrap/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="/include/css/main.css">
		<link rel="stylesheet" type="text/css" href="/include/css/navbar.css">
		<link rel="stylesheet" type="text/css" href="/include/css/sidebar.css">

		<script type="text/javascript"
				src="/include/lib/jquery/jquery.js"></script>
		<script type="text/javascript"
				src="/include/lib/bootstrap/bootstrap.js"></script>
		<script type="text/javascript" src="/include/js/navbar.js"></script>
		<script type="text/javascript" src="/include/js/sidebar.js"></script>

		<script type="text/javascript" src="/include/js/main.js"></script>
		<script type="text/javascript" src="/include/js/index.js"></script>
	</head>
	<body>
		<nav class="nav noselect">
			<ul>
				<li class="active" id="nbh">
					<a
						class="nbnav"
						href="/user/home.php"
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
							<li id='nbp'>
								<a
									class='nbnav'
									href='/project/projects.php'
									target='content'>
									Projectbeheer
								</a>
							</li>
						");
					if ($_COOKIE["gid"] == GID_ADMIN)
						echo("
							<li id='nbu'>
								<a
									class='nbnav'
									href='/user/users.php'
									target='content'>
									Gebruikersbeheer
								</a>
							</li>
							<li id='nbm'>
								<a
									class='nbnav'
									href='/editor/motd.php'
									target='content'>
									Welkomstbericht
								</a>
							</li>
						");
					echo("
						<li id='nbs'>
							<a
								class='nbnav'
								href='/user/settings.php'
								target='content'>
								Instellingen
							</a>
						</li>
					");
				?>
				<li class="right">
					<a href="/user/logout.php">
						Uitloggen
					</a>
				</li>
				<li class="right">
					<p class="navbar-text nopointer">
						<?php echo($_COOKIE["name"]) ?>
					</p>
				</li>
			</ul>
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

					while ($prow = $ptable->fetch_array()) { //XXX This is crap
						$qgtable =
							sprintf("SELECT pid, grp, uid FROM %s WHERE pid=%s",
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

					$ptable->close();
					$dbconn->close();

					echo("
						<div class='sidebar noselect'>
							<ol>
								<li class='active' id='sbh'>
									<a
										class='sbnav'
										href='/user/home.php'
										target='content'>
										Hoofdpagina
									</a>
								</li>
					");

					if ($_COOKIE["gid"] == GID_STUDENT)
						echo("
							<li id='sbr'>
								<a
									class='sbnav'
									href='/editor/cv.php'
									target='content'>
									Curriculum Vitae
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
												for='y" . $i . "'>"
												. $years[$i] . "
											</label>
											<input
												type='checkbox'
												class='sbcb'
												id='y" . $i . "'/>
											<ol>
									");
									$c = true;
								}

								echo("
									<li>
										<label
											for='p" . $j . "'>"
											. $projects[$j][1] . "
										</label>
										<input
											type='checkbox'
											class='sbcb'
											id='p" . $j . "'/>
										<ol>
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
				class="noselect"
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
