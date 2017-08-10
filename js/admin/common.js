/**
 * Created by jeyfost on 10.08.2017.
 */

function buttonHover(id, action) {
	if(action === 1) {
		document.getElementById(id).style.backgroundColor = "#34b1db";
		document.getElementById(id).style.color = "#fff";
	} else {
		document.getElementById(id).style.backgroundColor = "#dedede";
		document.getElementById(id).style.color = "#4c4c4c";
	}
}