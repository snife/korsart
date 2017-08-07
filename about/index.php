<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 13:45
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
		$pageResult = $mysqli->query("SELECT * FROM pages WHERE original_link = 'about/'");
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
		<img src="/img/system/about.jpg" />
	</div>

	<section>
		<div id="slogan">
			<p>Моя фотография умеет останавливать время и передавать эмоции.</p>
			<div style="width: 100%; text-align: left; margin-top: -60px;"><span style="font-family: 'Didact Gothic', sans-serif; color: #ccc; text-transform: uppercase; letter-spacing: 2px; font-size: 14px;"><q>Александр Корсаков</q></span></div>
		</div>
		<div id="about">
			<div id="aboutHeader"><h3>Здравствуйте! Давайте знакомиться!</h3></div>
			<p>Меня зовут Александр и мне нравится фотографировать. Нравится замечать интересные мгновения, общаться с людьми, путешествовать, делиться своими мыслями на снимках и не только. Моя фотография - это не профессия, это инструмент, при помощи которого я нахожу единомышленников, узнаю мир и развиваюсь как личность.</p>
			<p>Для меня всё началось в той самой тёмной комнате, где в тусклом свете красной лампы я впервые прочувствовал таинство получения настоящей фотографии. Я считаю, что фотография не самодостаточна, и для интересного кадра нужно что-то большее, чем простое умение создавать снимки. А сегодня, даже без камеры в руках, я не представляю себя кем-то другим. Я этим живу! И если вам интересны мои снимки, значит нам есть о чём поговорить!</p>
			<br />
			<div id="aboutHeader"><h3>Опыт моей работы:</h3></div>
			<ul>
				<li>Информационный портал <a href="https://tut.by/">TUT.BY</a></li>
				<li>Информационно-развлекательный портал <a href="http://www.chatoff.by/">&laquo;ChatOFF&raquo;</a></li>
				<li>Журнал <a href="http://ladys.by/">&laquo;Женский Журнал&raquo;</a></li>
				<li>Cвадебное агентство ART NOVIAS</li>
				<li>Cвадебное агентство &laquo;МЫ&raquo;</li>
				<li>Агентство &laquo;Шоу Альянс&raquo;</li>
				<li>Журнал <a href="http://www.alesyamag.by/">&laquo;Алеся&raquo;</a></li>
				<li>Журнал <a href="http://www.veselka.by/">&laquo;Вясёлка&raquo;</a></li>
				<li>freelance-съёмки с 2006 года</li>
			</ul>
			<br />
			<ul>
				<li><b>2006 г.</b> — курсы повышения квалификации РИВШ БГУ</li>
				<li><b>2007 г.</b> — курсы &laquo;Классической Фотографии&raquo; фотостудии &laquo;Мир Фото&raquo;</li>
				<li><b>2008-2009 гг.</b> — фото курсы &laquo;Центра Фотографии&raquo;</li>
				<li><b>2010 гг.</b> — победитель международной премии веб-журналистики &laquo;Yousmi web-journalism awards&raquo; в номинации &laquo;Лучший фоторепортаж&raquo; 2009 года</li>
				<li><b>2010 гг.</b> — курс &laquo;Портретная фотография&raquo; Андрея Дубинина (STUDIO67)</li>
				<li><b>2012 гг.</b> — курс &laquo;Портретная фотография&raquo; Егора Войнова (STUDIO67)</li>
				<li><b>2012 гг.</b> — курс фотожурналистики медиа-проектов (Jon Petter Evensen)</li>
				<li><b>2013 гг.</b> — курс фотожурналистики от Белорусской Ассоциации Журналистов (БАЖ)</li>
				<li><b>2015 гг.</b> — участник фотопроекта &laquo;Дружба Народов&raquo;, представлял Беларусь среди 15 стран</li>
			</ul>
			<br />
			<p>С 2014 года я занимаюсь лишь любительской фотографией и не работаю ни на одно издание или ресурс.</p>
		</div>
		<div style="clear: both;"></div>
	</section>

	<br /><br />
	<section class="bigSection">
		<div class="sectionHeader">
			<div class="sectionName">Следите за моими работами в Instagram</div>
			<br />
			<a href="https://www.instagram.com/2korsart/"><span class="smallFont">@2korsart</span></a>
		</div>
		<br />
		<div class="sectionHeader" style="margin-top: 40px;">
			<script type="text/javascript">
				var userFeed = new Instafeed({
					get: 'user',
					userId: '43983667',
					accessToken: '43983667.1677ed0.cec95463957d46abb616e422d10b38be'
				});

				userFeed.run();
			</script>

			<div id="instafeed"></div>
		</div>
	</section>

	<div onclick="scrollToTop()" id="scroll"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>

	<?= showFooter() ?>

	<script type="text/javascript" src="/js/main-slider.js"></script>
	<script type="text/javascript" src="/js/gallery-slider.js"></script>

</body>

</html>