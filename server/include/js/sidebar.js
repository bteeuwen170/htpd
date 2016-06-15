var sbprev = "sbh";

function sidebar_hide(admin)
{
	if (!admin) {
		document.getElementById("content").src = "about:blank";

		document.getElementsByClassName("sidebar")[0].style.display = "none";

		$(".sbcb").prop("checked", false); //FIXME I'm not sure why...
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

		sidebar_set("sbh");
	}
}

function sidebar_set(id)
{
	document.getElementById(sbprev).classList.remove("active");

	sbprev = id;

	document.getElementById(id).classList.add("active");
}
