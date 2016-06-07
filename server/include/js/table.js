var rows = new Array();

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

	$(".cb").click(function()
	{
		if (this.checked) {
			rows.push(this.value);
		} else {
			document.getElementById("sall").checked = false;
			rows.splice(rows.indexOf(this.value), 1);
		}

		row_updateui();
	});
});

function row_updateui()
{
	if (rows.length > 1) {
		document.getElementById("edit").disabled = true;
		document.getElementById("delete").disabled = false;
	} else if (rows.length > 0) {
		document.getElementById("edit").disabled = false;
		document.getElementById("delete").disabled = false;
	} else {
		document.getElementById("edit").disabled = true;
		document.getElementById("delete").disabled = true;
	}
}
