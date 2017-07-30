<?php

	function showMenu($page = null) {
		$menu = "
			<header>
				<a href='/' onmouseover='headerFontHover(\"left\", \"center\", \"right\", 1)' onmouseout='headerFontHover(\"left\", \"center\", \"right\", 0)'><span class='thinFont' id='left'>— </span><span class='headerFont' id='center'>Ваш Свадебный Фотограф</span><span class='thinFont' id='right'> —</span></a>
			</header>
			
			<div id='menu'>
				<div class='menuPoint'><span class='menuFont'>cвадебные галереи</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>love story</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>портрет</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>разное</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>отзывы</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>об авторе</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>контакты</span></div>
				<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>
				<div class='menuPoint'><span class='menuFont'>блог</span></div>
			</div>
			
			<div style='clear: both;'></div>
		";

		return $menu;
	}