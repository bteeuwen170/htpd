var rows = new Array();

function select_all(sa)
{
	var checkboxes = $(sa).closest("table").find(".cb");

	rows = new Array();

	for (i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = sa.checked;

		if (sa.checked)
			rows.push(checkboxes[i].value);
	}

	row_updateui();
}

function row_set(row)
{
	if (row.checked) {
		rows.push(row.value);
	} else {
		//document.getElementById("sall").checked = false;							FIXME Not working for secondary sall
		rows.splice(rows.indexOf(row.value), 1);
	}

	row_updateui();
}

function row_updateui() //FIXME Not if secondary
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
