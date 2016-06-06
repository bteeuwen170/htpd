function newpass_show(field)
{
	if ($(field).val().length > 0)
		document.getElementById("newpass").style.display = "inline";
	else
		document.getElementById("newpass").style.display = "none";
}
