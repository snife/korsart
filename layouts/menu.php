<?php

	function showMenu($page = null, $categories) {
		$menu = "
			<header>
				<a href='/' onmouseover='headerFontHover(\"left\", \"center\", \"right\", 1)' onmouseout='headerFontHover(\"left\", \"center\", \"right\", 0)'><span class='thinFont' id='left'>— </span><span class='headerFont' id='center'>Ваш Свадебный Фотограф</span><span class='thinFont' id='right'> —</span></a>
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

		$menu .= "</ul></menu>";

		return $menu;
	}