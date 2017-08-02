<?php
	include("scripts/connect.php");
	include("layouts/menu.php");
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
		$pageResult = $mysqli->query("SELECT * FROM pages WHERE original_link = 'index.php'");
		$page = $pageResult->fetch_assoc();

		$settingsResult = $mysqli->query("SELECT * FROM SETTINGS WHERE id = '1'");
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
	<link href="https://fonts.googleapis.com/css?family=Poiret+One" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Didact+Gothic" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Playfair+Display" rel="stylesheet">

	<link rel="stylesheet" type="text/css" href="/css/fonts.css" />
	<link rel="stylesheet" type="text/css" href="/css/main.css" />
	<link rel="stylesheet" type="text/css" href="/css/media.css" />
	<link rel="stylesheet" href="/plugins/font-awesome-4.7.0/css/font-awesome.css" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>

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

	<?= showMenu(null, $categories, $settings) ?>

	<div id="slider">
		<?php
			$i = 0;
			$sliderResult = $mysqli->query("SELECT * FROM main_slider ORDER BY id ASC");
			while($slider = $sliderResult->fetch_assoc()) {
				echo "<div class='slide"; if($i == 0) {echo " showing";} echo "'><img src='/img/photos/main_slider/".$slider['photo']."' /></div>";
			}
		?>
	</div>

	<br /><br />
	<section class="descriptionFont">
		<div style="width: 50%; position: relative; float: left; color: #bfbfbf; font-size: 18px; font-family: 'Didact Gothic', sans-serif; font-weight: bold;">Свадебный фотограф в Минске</div>
		<div style="width: 50%; position: relative; float: right; color: #bfbfbf; font-size: 18px; font-family: 'Didact Gothic', sans-serif; font-weight: bold;">ВДОХНОВЕНИЕ ДЛЯ СВАДЬБЫ</div>
		<div style="clear: both;"></div>
		<p>Легкость общения во время фотосессии, лаконичные фотографии: я помогу провести свадебный день в комфорте. Для меня главное - это передать атмосферу праздника и показать вашу естественную красоту, ваши чувства и эмоции.</p>
	</section>

	<br /><br />
	<div id="gallery-slider">
		<?php
			$i = 0;
			$gallerySliderResult = $mysqli->query("SELECT * FROM gallery_slider ORDER BY id ASC");
			while($gallerySlider = $gallerySliderResult->fetch_assoc()) {
				echo "<div class='gallery-slide"; if($i == 0) {echo " showing";} echo "'><img src='/img/photos/gallery_slider/".$gallerySlider['photo']."' /></div>";
			}
		?>
	</div>

	<script type="text/javascript" src="/js/main-slider.js"></script>
	<script type="text/javascript" src="/js/gallery-slider.js"></script>

</body>

</html>