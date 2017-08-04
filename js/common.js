$(window).on("load", function () {
	$('#sideMenu').height($(window).height());
});

window.onscroll = function() {scrollFunction()};

function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        document.getElementById("scroll").style.display = "block";
    } else {
        document.getElementById("scroll").style.display = "none";
    }
}

function scrollToTop() {
    $('html, body').animate({scrollTop: 0}, 500);
}

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

function showMobileMenu() {
	$('#sideMenu').fadeIn(300);
}

function hideMobileMenu() {
	$('#sideMenu').fadeOut(300);
}

function showMobileSubmenu(id) {
	var block = "sideSubmenu" + id;

	if($('#' + block).attr('visible') === 'yes') {
		$('#' + block).attr('visible', 'no');
		$('#' + block).hide('300');

		setTimeout(function () {
			$('#' + block).html();
		}, 300);
	} else {
		$.ajax({
			type: 'POST',
			data: {"id": id},
			url: "/scripts/menu/ajaxMobileSubmenu.php",
			success: function (response) {
				$('#' + block).attr('visible', 'yes');
				$('#' + block).html(response);
				$('#' + block).show('300');
			}
		});
	}
}