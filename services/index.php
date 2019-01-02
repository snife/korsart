<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 08.11.2017
 * Time: 11:19
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
		$pageResult = $mysqli->query("SELECT * FROM pages WHERE original_link = 'services/'");
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
	<script type="text/javascript" src="/js/instafeed.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
	<script type="text/javascript" src="/js/ya.js"></script>
	<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-108937733-1"></script>

    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-108937733-1');
    </script>

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

		$categoryResult = $mysqli->query("SELECT * FROM categories WHERE sef_link <> 'about' ORDER BY priority");
		while($category = $categoryResult->fetch_assoc()) {
			if($category['id'] == BLOG_ID) {
				$subcategoryResult = $mysqli->query("SELECT * FROM blog_subcategories ORDER BY priority");
			} else {
				$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$category['id']."' ORDER BY priority");
			}

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

	<!-- slider -->
	<!--
	<div id="slider">
		<img src="/img/system/services.jpg" id="mainIMG" />
	</div>

	<br /><br />
	-->
	<section class="bigSection">
		<div class="sectionHeader">
			<div class="line"></div>
			<div class="sectionName"><h1>Цены и услуги фотографа</h1></div>
			<div class="line"></div>
		</div>
		<br />
		<div class="sectionHeader" style="margin-top: 40px;">
			<?php
				$serviceResult = $mysqli->query("SELECT * FROM services ORDER BY id");
				while($service = $serviceResult->fetch_assoc()) {
					echo "
						<div class='serviceBlock' itemscope itemtype='http://schema.org/Product'>
							<div class='servicePhotoBlock'>
								<img itemprop='image' src='/img/services/".$service['photo']."' />
							</div>
							<div class='serviceTextBlock'>
								<div class='sectionHeader'>
									<h2 itemprop='name' class='serviceFont'>".$service['name']."</h2>
								</div>
								<br /><br />
								<p itemprop='description'>".$service['description']."</p>
								<div class='sectionHeader'>
									<div class='line'></div>
								</div>
								<ul>
					";

					$serviceCountResult = $mysqli->query("SELECT COUNT(id) FROM services_list WHERE service_id = '".$service['id']."'");
					$serviceCount = $serviceCountResult->fetch_array(MYSQLI_NUM);

					$i = 0;

					$serviceListResult = $mysqli->query("SELECT * FROM services_list WHERE service_id = '".$service['id']."' ORDER BY id");
					while($serviceList = $serviceListResult->fetch_assoc()) {
						$i++;

						if($i != $serviceCount[0]) {
							echo "<li>".$serviceList['text']."</li>";
						} else {
							echo "<li><b>".$serviceList['text']."</b></li>";
						}
					}

					echo "
								</ul>
							</div>
							<div style='clear: both;'></div>
						</div>
					";
				}
			?>
		</div>
	</section>

	<div onclick="scrollToTop()" id="scroll"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>

	<?= showFooter() ?>

<!--	<script type="text/javascript" src="/js/main-slider.js"></script>-->

</body>

</html>
