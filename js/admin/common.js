/**
 * Created by jeyfost on 10.08.2017.
 */

$(window).on("load", function () {
	if($('#leftMenu')) {
		if($('#leftMenu').height() < parseInt($(window).height() - $('#topLine').height())) {
			$('#leftMenu').height(parseInt($(window).height() - $('#topLine').height()));
		}
	}

	if($('#content')) {
		$('#content').width(parseInt($(window).width() - $('#leftMenu').width() - 130));
	}
});

function buttonHover(id, action) {
	if(action === 1) {
		document.getElementById(id).style.backgroundColor = "#34b1db";
		document.getElementById(id).style.color = "#fff";
	} else {
		document.getElementById(id).style.backgroundColor = "#dedede";
		document.getElementById(id).style.color = "#4c4c4c";
	}
}

function exit() {
	$.ajax({
		type: "POST",
		url: "/scripts/admin/ajaxExit.php",
		success: function (response) {
			window.location.href = "../";
		}
	});
}