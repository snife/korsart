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

$(window).on("resize", function () {
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

function buttonHoverRed(id, action) {
	if(action === 1) {
		document.getElementById(id).style.backgroundColor = "#d33434";
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

function textAreaHeight(textarea) {
    if (!textarea._tester) {
        var ta = textarea.cloneNode();
        ta.style.position = 'absolute';
        ta.style.zIndex = 2000000;
        ta.style.visibility = 'hidden';
        ta.style.height = '1px';
        ta.id = '';
        ta.name = '';
        textarea.parentNode.appendChild(ta);
        textarea._tester = ta;
        textarea._offset = ta.clientHeight - 1;
    }
    if (textarea._timer) clearTimeout(textarea._timer);
    textarea._timer = setTimeout(function () {
        textarea._tester.style.width = textarea.clientWidth + 'px';
        textarea._tester.value = textarea.value;
        textarea.style.height = (textarea._tester.scrollHeight - textarea._offset) + 'px';
        textarea._timer = false;
    }, 1);
}