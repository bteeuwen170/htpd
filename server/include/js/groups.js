$(document).ready(function()
{
	//$("#grouplist").tablesorter();
	$("#userlist").tablesorter();

	$(".cba").click(function()
	{
		if ($(".cba:checked").length)
			$("#adddsubmit").prop("disabled", false);
		else
			$("#adddsubmit").prop("disabled", true);
	});
});
