var prev;

function sidebar_hide()
{
	document.getElementById("content").style.marginLeft = 0;
	document.getElementById("content").style.width = "100%";
}

function sidebar_show()
{
	document.getElementById("content").removeAttribute("style");
}

function sidebar_set(id)
{
	if (prev != null)
		document.getElementById(prev).classList.remove("sidebar-item-sel");

	if (id == 0) {
		prev = null;
	} else {
		prev = id;

		document.getElementById(id).classList.add("sidebar-item-sel");
	}
}
