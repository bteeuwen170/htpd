$(document).ready(function()
{
	//$("#userlist").tablesorter();

	$("#editd").on("shown.bs.modal", function(e)
	{
		var row = document.getElementById("u" + rows[0]).children;
		$("#editduid").val(row[1].innerHTML);
		$("#editdname").val(row[2].innerHTML);
		$("#editdusername").val(row[3].innerHTML);
	});

	$("#importdupload:file").change(function()
	{
		$("#importdsubmit").prop("disabled", false);
	});
});
