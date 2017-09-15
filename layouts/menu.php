<?php

	function showMenu($page = null, $categories, $settings) {
		$menu = "
			<header>
				<div id='menuHeaderText'>
					<a href='/' onmouseover='headerFontHover(\"left\", \"center\", \"right\", 1)' onmouseout='headerFontHover(\"left\", \"center\", \"right\", 0)'><span class='thinFont' id='left'>— </span><span class='headerFont' id='center'>".$settings['site_header']."</span><span class='thinFont' id='right'> —</span></a>
				</div>
				<div id='mobileMenu' class='mobile' onclick='showMobileMenu()'><i class='fa fa-bars' aria-hidden='true'></i></div>
				<div id='sideMenu'>
					<div id='mobileMenu' class='mobile' onclick='hideMobileMenu()'><i class='fa fa-times' aria-hidden='true'></i></div>
					<div id='menuContent'>
		";

		foreach ($categories as $category) {
			if($category['subcategories']) {
				$menu .= "
					<div class='sideMenuPoint' onclick='showMobileSubmenu(\"".$category['id']."\")'>".mb_strtolower($category['name'])."<div class='sideSubmenu' id='sideSubmenu".$category['id']."'></div></div>
				";
			} else {
				$menu .= "
					<a href='/".$category['sef_link']."'><div class='sideMenuPoint'>".mb_strtolower($category['name'])."</div></a>
				";
			}
		}

		$menu .= "
					</div>
				</div>
			</header>
			<menu>
				<ul class='menu'>
		";

		$i = 1;

		foreach ($categories as $category) {
			$menu .= "
				<li class='menuPoint' id='menuPoint".$category['id']."' >
					<a href='".$category['sef_link']."'><span class='menuFont'>".mb_strtolower($category['name'])."</span>
			";

			if($i < count($categories)) {
				$menu .= "<i class='fa fa-circle' aria-hidden='true' style='padding-left: 25px; padding-right: 25px; color: #373737; font-size: 4px;'></i></a>";
			} else {
				$menu .= "</a>";
			}

			if($category['subcategories'])
			{
				$menu .= "<ul class='submenu'>";

				foreach ($category['subcategories'] as $subcategory) {
					$menu .= "
						<li class='subMenuLine'><a href='".$subcategory['sef_link']."' class='menuFont'>".mb_strtolower($subcategory['name'])."</a></li>
					";
				}

				$menu .= "</ul>";
			}

			$menu .= "</li>";

			$i++;
		}

		$menu .= "
				</ul>
			</menu>
		";

		return $menu;
	}

	function showMenu_2ndLevel($page = null, $categories, $settings) {
		$menu = "
			<header>
				<div id='menuHeaderText'>
					<a href='../' onmouseover='headerFontHover(\"left\", \"center\", \"right\", 1)' onmouseout='headerFontHover(\"left\", \"center\", \"right\", 0)'><span class='thinFont' id='left'>— </span><span class='headerFont' id='center'>".$settings['site_header']."</span><span class='thinFont' id='right'> —</span></a>
				</div>
				<div id='mobileMenu' class='mobile' onclick='showMobileMenu()'><i class='fa fa-bars' aria-hidden='true'></i></div>
				<div id='sideMenu'>
					<div id='mobileMenu' class='mobile' onclick='hideMobileMenu()'><i class='fa fa-times' aria-hidden='true'></i></div>
					<div id='menuContent'>
		";

		foreach ($categories as $category) {
			if($category['subcategories']) {
				$menu .= "
					<div class='sideMenuPoint' onclick='showMobileSubmenu(\"".$category['id']."\")'>".mb_strtolower($category['name'])."<div class='sideSubmenu' id='sideSubmenu".$category['id']."'></div></div>
				";
			} else {
				$menu .= "
					<a href='../".$category['sef_link']."'><div class='sideMenuPoint'>".mb_strtolower($category['name'])."</div></a>
				";
			}
		}

		$menu .= "
					</div>
				</div>
			</header>
			<menu>
				<ul class='menu'>
		";

		$i = 1;

		foreach ($categories as $category) {
			$menu .= "
				<li class='menuPoint' id='menuPoint".$category['id']."' >
					<a href='../".$category['sef_link']."'><span class='menuFont'>".mb_strtolower($category['name'])."</span>
			";

			if($i < count($categories)) {
				$menu .= "<i class='fa fa-circle' aria-hidden='true' style='padding-left: 25px; padding-right: 25px; color: #373737; font-size: 4px;'></i></a>";
			} else {
				$menu .= "</a>";
			}

			if($category['subcategories'])
			{
				$menu .= "<ul class='submenu'>";

				foreach ($category['subcategories'] as $subcategory) {
					$menu .= "
						<li class='subMenuLine'><a href='../".$subcategory['sef_link']."' class='menuFont'>".mb_strtolower($subcategory['name'])."</a></li>
					";
				}

				$menu .= "</ul>";
			}

			$menu .= "</li>";

			$i++;
		}

		$menu .= "
				</ul>
			</menu>
		";

		return $menu;
	}