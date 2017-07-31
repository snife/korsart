<?php

	function showMenu($page = null, $categories, $subcategories) {
		$menu = "
			<header>
				<a href='/' onmouseover='headerFontHover(\"left\", \"center\", \"right\", 1)' onmouseout='headerFontHover(\"left\", \"center\", \"right\", 0)'><span class='thinFont' id='left'>— </span><span class='headerFont' id='center'>Ваш Свадебный Фотограф</span><span class='thinFont' id='right'> —</span></a>
			</header>
			<div id='menu'>
		";

		$i = 1;
		foreach ($categories as $category) {
			$menu .= "<div class='menuPoint'><span class='menuFont'>".mb_strtolower($category['name'])."</span></div>";

			if($i < count($categories)) {
				$menu .= "<div class='menuDot'><i class='fa fa-circle' aria-hidden='true'></i></div>";
			}

			$i++;
		}

		$menu .= "</div>";
		return $menu;
	}