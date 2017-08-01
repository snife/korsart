var category = "";

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

function submenu(id) {
	if(id === '') {
		id = category;
	} else {
		category = id;
	}

	var menuPoint = "menuPoint" + id;

	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/menu/ajaxSubmenu.php",
		success: function (response) {
			var top = $('#' + menuPoint).offset().top;
			var left = $('#' + menuPoint).offset().left;

			$('#subMenu').offset({top: parseInt(top + 22)});
			$('#subMenu').offset({left: left});

			$('#subMenu').html(response);

			$('#subMenu').css('opacity', '1');
		}
	});
}

function hideSubmenu() {
		$('#subMenu').css('opacity', '0');

		setTimeout(function () {
			$('#subMenu').html("");
		}, 300);
}