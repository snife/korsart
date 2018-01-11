<?php
	include("scripts/connect.php");
	include("layouts/menu.php");
	include("layouts/footer.php");
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
	<script type="text/javascript" src="/js/index.js"></script>
	<script type="text/javascript" async="" src="https://mc.yandex.ru/metrika/watch.js"></script>
	<script type="text/javascript" src="/js/ya.js"></script>

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

	<?= showMenu(null, $categories, $settings) ?>

	<div id="slider">
		<?php
			$i = 0;
			$sliderResult = $mysqli->query("SELECT * FROM main_slider ORDER BY id ASC");
			while($slider = $sliderResult->fetch_assoc()) {
				echo "<div class='slide"; if($i == 0) {echo " showing";} echo "'><img src='/img/photos/main_slider/".$slider['photo']."' /></div>";
				$i++;
			}
		?>
	</div>

	<br />
    <br />
	<section class="descriptionFont">
		<div style="width: 50%; position: relative; float: left; color: #bfbfbf; font-size: 14px; font-family: 'Didact Gothic', sans-serif; font-weight: bold; letter-spacing: 2px;">Свадебный фотограф в Минске</div>
		<div style="width: 50%; position: relative; float: right; color: #bfbfbf; font-size: 14px; font-family: 'Didact Gothic', sans-serif; font-weight: bold; letter-spacing: 2px;">ВДОХНОВЕНИЕ ДЛЯ СВАДЬБЫ</div>
		<div style="clear: both;"></div>
		<p style="text-align: justify;">Легкость общения во время фотосессии, лаконичные фотографии: я помогу провести свадебный день в комфорте. Для меня главное - это передать атмосферу праздника и показать вашу естественную красоту, ваши чувства и эмоции.</p>
	</section>

	<br />
    <br />
	<div id="gallery-slider">
		<?php
			$i = 0;
			$gallerySliderResult = $mysqli->query("SELECT * FROM gallery_slider ORDER BY id ASC");
			while($gallerySlider = $gallerySliderResult->fetch_assoc()) {
				echo "<div class='gallery-slide"; if($i == 0) {echo " showing";} echo "'><img src='/img/photos/gallery_slider/".$gallerySlider['photo']."' /></div>";
				$i++;
			}
		?>

		<?php
			$weddingResult = $mysqli->query("SELECT * FROM categories WHERE id = '1'");
			$wedding = $weddingResult->fetch_assoc();

			$storyResult = $mysqli->query("SELECT * FROM categories WHERE id = '2'");
			$story = $storyResult->fetch_assoc();
		?>

		<div id="sliderLinks">
			<div class="sliderLinkSection">
				<a href='/<?= $wedding['sef_link'] ?>'><span class="sliderLinkFont">Свадьба</span></a>
				<br /><br />
				<span style="font-size: 12px; font-family: 'Didact Gothic', sans-serif; letter-spacing: 2px; text-transform: uppercase; color: #fff;">Фотогалерея</span>
				<div style="width: 1px; background-color: #fff; height: 100%; position: absolute; top: 25%; left: 50%; z-index: 3; opacity: .8;"></div>
				<div style="clear: both;"></div>
			</div>
			<div class="sliderLinkSection">
				<a href="/<?= $story['sef_link'] ?>"><span class="sliderLinkFont">Love Story</span></a>
				<br /><br />
				<span style="font-size: 12px; font-family: 'Didact Gothic', sans-serif; letter-spacing: 2px; text-transform: uppercase; color: #fff;">Фотогалерея</span>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>

	<br />
    <br />
	<section class="descriptionFont">
		<div style="width: 100%; color: #474747; font-size: 16px; font-family: 'Didact Gothic', sans-serif; letter-spacing: 1px; text-align: right;">Давайте сделаем красивую историю о вас!</div>
		<p style="text-align: justify;">Снимки "с настроением" уникальны. Они не выходят из моды, их всегда интересно смотреть. Спустя годы их не стыдно показать своим детям и внукам. Это творчество и документалистика в чистом виде. Всё это - ваша свадебная фотосессия.</p>
	</section>

	<br />
    <br />
	<section class="bigSection">
		<div class="sectionHeader">
			<div class="line"></div>
			<div class="sectionName">Отзывы</div>
			<div class="line"></div>
		</div>
		<br />
		<div class="sectionHeader" style="margin-top: 40px;">
			<div class="mainReview">
				<img src="/img/system/review1.png" />
				<br /><br />
				<span class="mainReviewHeaderFont"><strong>Таня и Ваня<br /><span style=" font-size: 16px;">v</span></strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>С талантом и работой Саши мы знакомы не понаслышке, а лично. Поэтому у нас не возник вопрос, кто будет фотографом нашей свадьбы. Главный вопрос стоял в согласии Саши быть гостем и фотографом одновременно! Мы были рады услышать положительный ответ! Работать с Сашей одно удовольствие!!! Мы наслаждались процессом съемки: теплая дружественная атмосфера, ненавязчивость и отсутствие шаблонов, - впрочем, как и всегда. Результат не заставил себя долго ждать. Мы уже несколько дней пересматриваем свадебные фото, каждый раз снова и снова погружаясь в воспоминания и переживаем все те эмоции, которые испытывали в "Наш Свадебный День". Саша, мы хотим сказать тебе огромное спасибо за то, что ты был с нами в этот главный для нас день!</p>
				</div>
			</div>
			<div class="mainReview">
				<img src="/img/system/review2.png" />
				<br /><br />
				<span class="mainReviewHeaderFont"><strong>Наташа и Игорь<br /><span style=" font-size: 16px;">v</span></strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Замечательный свадебный фотограф! Замечательные фотографии! Хочется пересматривать фотокнигу снова и снова и всегда с улыбкой, настолько она передает атмосферу свадебного дня! :) Спасибо тебе, Саша, большое! Нам повезло, что мы встретили тебя!</p>
				</div>
			</div>
			<div class="mainReview">
				<img src="/img/system/review3.png" />
				<br /><br />
				<span class="mainReviewHeaderFont"><strong>Настя и Дима<br /><span style=" font-size: 16px;">v</span></strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>С творчеством Саши познакомился в ходе его лекции в БГУ. Сразу понравились его подход и отношение к коммерческой съемке. Так что когда возник вопрос с выбором фотографа на свадьбу - долго не думали, а быстро записались на нужную дату. И он в полной мере оправдал наши ожидания. На мой взгляд, Саша у других свадебных фотографов дополнительно выигрывает за счет большого опыта фотожурналиста, ни один интересный момент действа в праздничный день не был утерян. Его съемка - это не статичные, замыленные позы, а действительно живая и осмысленная передача действа. Пересматривая фотографии, ты каждый раз переживаешь этот день сполна. По итогу мы получили огромнейшее количество отличнейшего материала, обалденный альбом и оформленные диски. И самое главное, долгую и позитивную память о нашей свадьбе!</p>
				</div>
			</div>
			<br /><br />
			<a href="/reviews"><input type="button" id="allReviewsButton" value="Больше отзывов" onmouseover="reviewsButton(1)" onmouseout="reviewsButton(0)" /></a>
		</div>
	</section>

	<br />
    <br />
	<section class="bigSection">
		<div class="sectionHeader">
			<div class="line"></div>
			<div class="sectionName">Почему я?</div>
			<div class="line"></div>
		</div>
		<br />
		<div class="sectionHeader" style="margin-top: 40px;">
			<div class="quarterBlock">
				<img src="/img/system/computer.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Качество</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Все фотографии обрабатываются в фоторедакторах, а при заказе фотокниг снимки проходят дополнительную допечатную ретушь.</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/clock.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Оперативность</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Время на подготовку и обработку электронной версии вашей свадьбы - не более двух месяцев.</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/car.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Мобильность</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Я работаю по всей стране и могу быстро добраться к вам на съёмку на автомобиле.</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/shield.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Гарантии</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>При работе заключается договор. Вы получаете полную информацию о сроках и условиях съёмки. Никаких "серых" схем работы.</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/hat.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Опыт</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Работа в области фотографии с 2006 года. Опыт съёмок в печатных и электронных СМИ, несколько лет работы в масс-медиа, стажировка за рубежом.</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/award.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Награды</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Победитель международной премии веб-журналистики Yousmi web-journalism awards в номинации "Лучший фоторепортаж". Участник международного фотопроекта "Дружба Народов".</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/handshake.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Ответственность</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>Съёмка выполняется на современную технику в несколько камер и объективов. Архивное хранение снимков и бесплатное восстановление в течение 2 лет.</p>
				</div>
			</div>
			<div class="quarterBlock">
				<img src="/img/system/check.jpg" />
				<br /><br />
				<span class="mainReviewHeaderFont" style="text-transform: uppercase; font-size: 16px;"><strong>Полиграфия</strong></span>
				<br /><br />
				<div class="mainReviewText">
					<p>В любой момент вы можете заказать печать фотокниги своей свадьбы. Я помогу выбрать лучший дизайн и сориентирую в ценах типографии.</p>
				</div>
			</div>
		</div>
	</section>

	<br />
    <br />
	<section class="bigSection">
		<div class="sectionHeader">
			<a href="/services"><input type="button" id="servicesButton" value="Услуги и цены" onmouseover="servicesButton(1)" onmouseout="servicesButton(0)" /></a>
		</div>
	</section>

	<br />
    <br />
	<section class="bigSection">
		<div class="sectionHeader">
			<div class="line"></div>
			<div class="sectionName">Давайте знакомиться</div>
			<div class="line"></div>
		</div>
		<br />
		<div class="sectionHeader" style="margin-top: 40px;">
			<div class="serviceBlock" style="border: none;">
				<div class="servicePhotoBlock">
					<img src="/img/system/me.jpg" />
				</div>
				<div class="serviceTextBlock">
					<div class="sectionHeader">
						<span class="serviceFont">Обо мне</span>
					</div>
					<br />
                    <br />
					<p>Меня зовут Александр, и мне нравится фотографировать. Нравится замечать интересные мгновения, общаться с людьми, путешествовать, делиться своими мыслями на снимках и не только.</p>
				</div>
				<br />
				<div class="serviceTextBlock" style="margin-top: 20px;">
					<div class="sectionHeader">
						<span class="serviceFont">Контакты</span>
					</div>
					<br />
                    <br />
					<p>Я всегда открыт новым предложениям и неожиданным путешествиям.</p>
					<ul>
						<li>&nbsp;<i class="fa fa-mobile" aria-hidden="true"></i>&nbsp;&nbsp;GSM +375 (29) 606 35 91</li>
						<li>&nbsp;<i class="fa fa-mobile" aria-hidden="true"></i>&nbsp;&nbsp;GSM +375 (29) 505 35 91</li>
						<li><i class="fa fa-envelope-open-o" aria-hidden="true"></i> Viber +375 (29) 606 35 91</li>
					</ul>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</section>

	<section class="bigSection">
		<div class="sectionHeader">
			<div class="sectionName">Следите за моими работами в Instagram</div>
			<br />
			<a href="https://www.instagram.com/korsart_wedding/"><span class="smallFont">@korsart_wedding</span></a>
		</div>
		<br />
		<div class="sectionHeader" style="margin-top: 40px;">
			<script type="text/javascript">
				var userFeed = new Instafeed({
					get: 'user',
					userId: <?= "'".INSTAGRAM_USER_ID."'" ?>,
					accessToken: <?= "'".INSTAGRAM_ACCESS_TOKEN."'" ?>
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