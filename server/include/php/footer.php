<?php
	$cy = date("Y");
	echo("
		<footer class='footer nopointer noselect'>
			<div class='container'>
				<p class='text-muted'>HTPD " . VERSION . " © " .
						(($cy == 2016) ? "" : "2016 - ") . $cy . "</p>
			</div>
		</footer>
	");
?>
