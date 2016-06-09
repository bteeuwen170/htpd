var nbprev = "nbh";

function navbar_set(id)
{
	document.getElementById(nbprev).classList.remove("active");

	nbprev = id;

	document.getElementById(id).classList.add("active");
}
