<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 14:39
 */

include("../scripts/connect.php");
include("../layouts/menu.php");
include("../layouts/footer.php");

?>

<!DOCTYPE html>

<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->

<head>

	<?php
		$pageResult = $mysqli->query("SELECT * FROM pages WHERE original_link = 'reviews/'");
		$page = $pageResult->fetch_assoc();

		$settingsResult = $mysqli->query("SELECT * FROM settings WHERE id = '1'");
		$settings = $settingsResult->fetch_assoc();
	?>

	<meta charset="utf-8" />

	<title><?= $page['title'] ?></title>

	<meta name="description" content="<?= $page['description'] ?>" />
	<meta name="keywords" content="<?= $page['keywords'] ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="shortcut icon" href="/img/system/favicon.ico" />

	<link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Arimo" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Didact+Gothic" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="/css/fonts.css" />
	<link rel="stylesheet" type="text/css" href="/css/main.css" />
	<link rel="stylesheet" type="text/css" href="/css/media.css" />
	<link rel="stylesheet" href="/plugins/font-awesome-4.7.0/css/font-awesome.css" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
	<script type="text/javascript" src="/js/contacts.js"></script>


	<style>
		#page-preloader {position: fixed; left: 0; top: 0; right: 0; bottom: 0; background: #fff; z-index: 100500;}
		#page-preloader .spinner {width: 32px; height: 32px; position: absolute; left: 50%; top: 50%; background: url('/img/system/spinner.gif') no-repeat 50% 50%; margin: -16px 0 0 -16px;}
	</style>

    <script type="text/javascript">
        $(window).on('load', function () {
            var $preloader = $('#page-preloader'), $spinner = $preloader.find('.spinner');
            $spinner.delay(500).fadeOut();
            $preloader.delay(850).fadeOut();
        });
    </script>

	<!-- Yandex.Metrika counter --><!-- /Yandex.Metrika counter -->
	<!-- Google Analytics counter --><!-- /Google Analytics counter -->
</head>

<body>
	<div id="page-preloader"><span class="spinner"></span></div>

	<?php
		$categories = array();

		$categoryResult = $mysqli->query("SELECT * FROM categories ORDER BY priority");
		while($category = $categoryResult->fetch_assoc()) {
			$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$category['id']."' ORDER BY priority");
			if($subcategoryResult->num_rows > 0) {
				$subcategories = array();

				while($subcategory = $subcategoryResult->fetch_assoc()) {
					array_push($subcategories, $subcategory);
				}

				$category['subcategories'] = $subcategories;
			}
			array_push($categories, $category);
		}

	?>

	<?= showMenu_2ndLevel(null, $categories, $settings) ?>

	<div id="slider">
		<img src="/img/system/contacts.jpg" />
	</div>

	<br /><br />
	<section style="border-left: 1px solid #ddd; border-right: 1px solid #ddd;">
		<p style="">Я всегда открыт новым предложениям и неожиданным путешествиям. Если у вас есть идея по фотосъемке — свадебной, love story, портретной — пишите мне. Я буду рад воплотить самую сумасшедшую фотоидею.</p>
	</section>

	<div onclick="scrollToTop()" id="scroll"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>

	<?= showFooter() ?>

	<script type="text/javascript" src="/js/main-slider.js"></script>
	<script type="text/javascript" src="/js/gallery-slider.js"></script>

</body>

</html>