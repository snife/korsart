<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 04.08.2017
 * Time: 11:32
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
	<link rel="stylesheet" href="/plugins/Remodal-1.1.1/dist/remodal.css">
	<link rel="stylesheet" href="/plugins/Remodal-1.1.1/dist/remodal-default-theme.css">

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/plugins/Remodal-1.1.1/dist/remodal.min.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
	<script type="text/javascript" src="/js/reviews.js"></script>


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
		<img src="/img/system/reviews.jpg" />
	</div>

	<div id="newReviewButtonContainer">
		<a data-remodal-target="modal"><input type="button" id="newReviewButton" value="Оставить отзыв" onmouseover="changeNewReviewButton(1)" onmouseout="changeNewReviewButton(0)" /></a>
	</div>

	<br /><br />
	<section class="bigSection" style="margin-top: 120px;">
		<?php
			$i = 1;
			$reviewResult = $mysqli->query("SELECT * FROM reviews WHERE showing = '1' ORDER BY id DESC");
			while($review = $reviewResult->fetch_assoc()) {
				echo "
					<div class='reviewContainer'>
				";

				if($i % 2 == 0) {
					echo "
						<div class='reviewPhoto'>"; if(!empty($review['photo'])) {echo "<img src='/img/photos/reviews/".$review['photo']."' />";} echo "</div>
						<div class='review'>
							<div class='reviewHeader'><h1>".$review['name']."</h1></div>
							<p>".$review['text']."</p>
						</div>
					";
				} else {
					echo "
						<div class='review'>
							<div class='reviewHeader'><h1>".$review['name']."</h1></div>
							<p>".$review['text']."</p>
						</div>
						<div class='reviewPhoto'>"; if(!empty($review['photo'])) {echo "<img src='/img/photos/reviews/".$review['photo']."' />";} echo "</div>
					";
				}

				echo "
					</div>
				";

				$i++;
			}
		?>
	</section>

	<br /><br />
	<section>
		<div class="sectionHeader">
			<div class="line"></div>
			<div class="sectionName">Оставьте свой отзыв</div>
			<div class="line"></div>
		</div>
		<div class="sectionHeader" style="margin-top: 40px;">
			<form method="post" id="reviewForm">
				<input id="reviewNameInput" name="reviewName" placeholder="Имя" />
				<br /><br />
				<input id="reviewEmailInput" name="reviewEmail" placeholder="Ваш email" />
				<br /><br />
				<textarea id="reviewTextInput" name="reviewText" placeholder="Ваш отзыв"></textarea>
				<br /><br />
				<input type="file" id="reviewPhotoInput" name="reviewPhoto" />
				<br /><br />
				<input type="button" id="reviewSubmit" value="Оставить отзыв" onclick="sendReviewFromPage()" />
			</form>
		</div>
	</section>

	<div class="remodal" data-remodal-id="modal" data-remodal-options="closeOnConfirm: false">
		<button data-remodal-action="close" class="remodal-close"></button>
		<div style='width: 80%; margin: 0 auto;'><h1>Пожалуйста, оставьте свой отзыв.<br />Для меня это очень важно!</h1></div>
		<br /><br />
			<form method="post" id="modalForm">
				<input id="nameInput" name="name" placeholder="Имя" />
				<br /><br />
				<input id="emailInput" name="email" placeholder="E-mail" />
				<br /><br />
				<textarea id="textInput" name="text" placeholder="Отзыв"></textarea>
				<br /><br />
				<input type="file" id="photoInput" name="photo" />
			</form>
		<br />
		<button data-remodal-action="confirm" class="remodal-confirm" style="width: 150px;" onclick="sendReview()">Оставить отзыв</button>
	</div>

	<div onclick="scrollToTop()" id="scroll"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>

	<?= showFooter() ?>

	<script type="text/javascript" src="/js/main-slider.js"></script>
	<script type="text/javascript" src="/js/gallery-slider.js"></script>

</body>

</html>