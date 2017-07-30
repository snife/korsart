function headerFontHover(left, center, right, action) {
	if(action === 1) {
		document.getElementById(left).style.color = "#262626";
		document.getElementById(center).style.color = "#262626";
		document.getElementById(right).style.color = "#262626";
	} else {
		document.getElementById(left).style.color = "#474747";
		document.getElementById(center).style.color = "#474747";
		document.getElementById(right).style.color = "#474747";
	}
}