var rows = new Array();
var cbprev;

$(document).ready(function()
{
	$("#sall").click(function()
	{
		var checkboxes = $(this).closest("table").find(".cb");

		rows = new Array();

		for (i = 0; i < checkboxes.length; i++) {
			checkboxes[i].checked = this.checked;

			if (this.checked)
				rows.push(checkboxes[i].value);
		}

		row_updateui();
	});

	$(".cb").click(function(e)
	{
		/*if (!cbprev) {
			cbprev = this;
		} else {
			if (e.shiftKey) {
				var start = $(".cb").index(this);
				var end = $(".cb").index(this);

				$(".cb").slice(Math.min(start, end), Math.max(start, end) + 1).prop("checked", cbprev.checked));
			}
		}*/

		if (this.checked) {
			rows.push(this.value);
		} else {
			document.getElementById("sall").checked = false;
			rows.splice(rows.indexOf(this.value), 1);
		}

		cbprev = this;
		row_updateui();
	});
});

function row_updateui()
{
	var btnedit = document.getElementById("edit");
	var btndelete = document.getElementById("delete");

	if (rows.length > 1) {
		if (btnedit)
			btnedit.disabled = true;
		if (btndelete)
			btndelete.disabled = false;
	} else if (rows.length > 0) {
		if (btnedit)
			btnedit.disabled = false;
		if (btndelete)
			btndelete.disabled = false;
	} else {
		if (btnedit)
			btnedit.disabled = true;
		if (btndelete)
			btndelete.disabled = true;
	}
}
