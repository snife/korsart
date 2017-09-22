<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 21:46
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
	header("Location: ../../");
}

if(!empty($_REQUEST['id'])) {
	$reviewCheckResult = $mysqli->query("SELECT COUNT(id) FROM reviews WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
	$reviewCheck = $reviewCheckResult->fetch_array(MYSQLI_NUM);

	if($reviewCheck[0] == 0) {
		header("Location: index.php");
	}
}

?>

<!DOCTYPE html>

<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->

<head>

	<meta charset="utf-8" />

	<title>Панель администрирования | Отзывы</title>

	<meta name="description" content="" />
	<meta name="keywords" content="" />
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
	<link rel="stylesheet" type="text/css" href="/css/admin.css" />
	<link rel="stylesheet" href="/plugins/font-awesome-4.7.0/css/font-awesome.css" />
	<link rel="stylesheet" type="text/css" href="/plugins/lightview/css/lightview/lightview.css" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/plugins/lightview/js/lightview/lightview.js"></script>
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/js/admin/reviews/index.js"></script>

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

	<div id="topLine">
		<div id="logo">
			<a href="/"><span><i class="fa fa-home" aria-hidden="true"></i> korsart.by</span></a>
		</div>
		<a href="/admin/admin.php"><span class="headerText">Панель администрирвания</span></a>
		<div id="exit" onclick="exit()">
			<span>Выйти <i class="fa fa-sign-out" aria-hidden="true"></i></span>
		</div>
	</div>
	<div id="leftMenu">
		<a href="/admin/pages/">
			<div class="menuPoint">
				<i class="fa fa-file-text-o" aria-hidden="true"></i><span> Страницы</span>
			</div>
		</a>
		<a href="/admin/sliders/">
			<div class="menuPoint">
				<i class="fa fa-picture-o" aria-hidden="true"></i><span> Слайдеры</span>
			</div>
		</a>
		<a href="/admin/reviews/">
			<div class="menuPoint active">
				<i class="fa fa-commenting" aria-hidden="true"></i><span> Отзывы</span>
			</div>
		</a>
		<a href="/admin/galleries/">
			<div class="menuPoint">
				<i class="fa fa-camera" aria-hidden="true"></i><span> Галереи</span>
			</div>
		</a>
		<a href="/admin/blog/">
			<div class="menuPoint">
				<i class="fa fa-bullhorn" aria-hidden="true"></i><span> Блог</span>
			</div>
		</a>
		<a href="/admin/security/">
			<div class="menuPoint">
				<i class="fa fa-shield" aria-hidden="true"></i><span> Безопасность</span>
			</div>
		</a>
	</div>

	<div id="content">
		<span class="headerFont">Отзывы клиентов</span>
		<br /><br />
		<div id="reviews">
			<?php
				if(empty($_REQUEST['id'])) {
					$reviewResult = $mysqli->query("SELECT * FROM reviews ORDER BY id DESC");
					while($review = $reviewResult->fetch_assoc()) {
						echo "
							<div class='reviewContainer'>
								<div class='reviewPhoto'>
									<img src='/img/photos/reviews/".$review['photo']."' />
								</div>
								<div class='review'>
									<div class='reviewHeader'><h3>".$review['name']."</h3></div>
									<p>".$review['text']."</p>
									<p class='email'><a href='mailto: ".$review['email']."'>".$review['email']."</a></p>
								</div>
								<div class='reviewButtons'>
									<form method='post'>
										<label for='showButton'>Отображать? </label>
										<input type='checkbox' id='showButton".$review['id']."' name='show' onclick='showReview(\"".$review['id']."\", \"showButton".$review['id']."\")' "; if($review['showing'] == 1) {echo "checked";} echo ">
									</form>
									<br />
									<a href='index.php?id=".$review['id']."'><span><i class='fa fa-pencil-square' aria-hidden='true'></i> Редактировать</span></a>
									<br /><br />
									<span onclick='deleteReview(\"".$review['id']."\")'><i class='fa fa-trash' aria-hidden='true'></i> Удалить</span>
								</div>
							</div>
						";
					}
				} else {
					$reviewResult = $mysqli->query("SELECT * FROM reviews WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
					$review = $reviewResult->fetch_assoc();

					echo "
						<a href='index.php' class='adminLink'><i class='fa fa-backward' aria-hidden='true'></i> Вернуться к списку отзывов</a>
						<br /><br />
						<form method='post' id='reviewEditForm'>
							<label for='nameInput'>Имя:</label>
							<br />
							<input id='nameInput' name='name' value='".$review['name']."' />
							<br /><br />
							<label for='photoInput'>Фотография:</label>
							<br />
							<a href='/img/photos/reviews/".$review['photo']."' class='lightview' data-lightview-options='skin: \"light\"'><span>Посмотреть текущую фотографию</span></a>
							<br /><br />
							<input type='file' id='photoInput' name='photo' style='padding-top: 10px;' />
							<br /><br />
							<label for='textInput'>Текст отзыва:</label>
							<br />
							<textarea id='textInput' name='text' onkeydown='textAreaHeight(this)'>".str_replace("<br />", "", $review['text'])."</textarea>
							<br /><br />
							<input type='button' id='reviewSubmit' value='Редактировать' onmouseover='buttonHover(\"reviewSubmit\", 1)' onmouseout='buttonHover(\"reviewSubmit\", 0)' class='button' onclick='editReview(\"".$review['id']."\")' />
						</form> 
					";
				}
			?>
		</div>
	</div>

</body>

</html>