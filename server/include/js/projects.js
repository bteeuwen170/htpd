$(document).ready(function()
{
	//$("#grouplist").tablesorter();

	$(".pr").click(function()
	{
		location.href = "groups.php?pid=" +
				$(this).closest("tr").attr("id").slice(1);
	});

	$("#editd").on("shown.bs.modal", function(e)
	{
		var row = document.getElementById("p" + rows[0]).children;
		$("#editdpid").val(row[1].innerHTML);
		$("#editdname").val(row[2].innerHTML);
		$("#editdgroups").val(row[3].innerHTML);
		$("#editdyear").val(row[4].getAttribute("data-year"));
	});
});
