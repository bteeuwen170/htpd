var prev;

function sidebar_hide(admin)
{
	if (!admin) {
		document.getElementById("content").src = "about:blank";

		document.getElementsByClassName("sidebar")[0].style.display = "none";
	}

	document.getElementById("content").style.marginLeft = 0;
	document.getElementById("content").style.width = "100%";
}

function sidebar_show(admin)
{
	if (!admin) {
		document.getElementById("content").src = "about:blank";

		document.getElementsByClassName("sidebar")[0].style.display = "inline";
		document.getElementById("content").removeAttribute("style");
	}
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
