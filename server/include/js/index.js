function cookie_get(cname) {
	var name = cname + "=";
	var ca = document.cookie.split(';');

	for (i = 0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ')
			c = c.substring(1);
		if (c.indexOf(name) == 0)
			return c.substring(name.length,c.length);
	}
	
	return "";
}

var admin;

$(document).ready(function()
{
	admin = (cookie_get("gid") == 0) ? 1 : 0;

	if (admin)
		sidebar_hide(admin);

	$(".nbnav").click(function()
	{
		var id = $(this).closest("li").attr("id");

		if (id == "nbh")
			sidebar_show(admin);
		else
			sidebar_hide(admin);

		navbar_set(id);
	});

	$(".sbnav").click(function()
	{
		sidebar_set($(this).closest("li").attr("id"));
	});
});
