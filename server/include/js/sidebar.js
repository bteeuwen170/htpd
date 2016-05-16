var prev;

function set_sidebar(id)
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
