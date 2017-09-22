<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 19:40
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
	header("Location: ../../");
}

if(!empty($_REQUEST['id'])) {
	if($_REQUEST['id'] != 1 and $_REQUEST['id'] != 2) {
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

	<title>Панель администрирования | Слайдеры</title>

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
	<script type="text/javascript" src="/js/admin/sliders/index.js"></script>

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
			<div class="menuPoint active">
				<i class="fa fa-picture-o" aria-hidden="true"></i><span> Слайдеры</span>
			</div>
		</a>
		<a href="/admin/reviews/">
			<div class="menuPoint">
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
		<span class="headerFont">Работа с фотографиями в слайдерах</span>
		<br /><br />
		<form method="post" id="sliderForm">
			<label for="sliderSelect"></label>
			<select id="sliderSelect" name="page" onchange="window.location = '?id=' + this.options[this.selectedIndex].value">
				<option value="">- Выберите страницу -</option>
				<option value="1" <?php if($_REQUEST['id'] == 1) {echo "selected";} ?>>Первый слайдер</option>
				<option value="2" <?php if($_REQUEST['id'] == 2) {echo "selected";} ?>>Второй слайдер</option>
			</select>
			<?php
				if(!empty($_REQUEST['id'])) {
					switch ($_REQUEST['id']) {
						case 1:
							$tableName = "main_slider";
							break;
						case 2:
							$tableName = "gallery_slider";
							break;
						default:
							break;
					}

					echo "
						<br /><br />
						<div id='sliderPhotosContainer'>
					";

					$sliderResult = $mysqli->query("SELECT * FROM ".$tableName." ORDER BY id");
					while($slider = $sliderResult->fetch_assoc()) {
						echo "
							<div class='sliderPhotoPreview'>
								<a href='/img/photos/".$tableName."/".$slider['photo']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='slider'><img src='/img/photos/".$tableName."/".$slider['photo']."' /></a>
								<br />
								<span onclick='deletePhoto(\"".$slider['id']."\", \"".$tableName."\")'>Удалить</span>
							</div>
						";
					}

					echo "
							</div>
						</form>
						<br /><br />
						<h3>Добавление фотографий</h3>
						<form method='post' id='addForm' enctype='multipart/form-data'>
						<label for='photosInput'>Выберите фотграфии:</label>
						<br />
						<input type='file' class='file' id='sliderPhotosInput' name='sliderPhotos[]' multiple />
						<br /><br />
						<input type='button' class='button' id='sliderPhotosSubmit' value='Добавить' onmouseover='buttonHover(\"sliderPhotosSubmit\", 1)' onmouseout='buttonHover(\"sliderPhotosSubmit\", 0)' onclick='add(\"".$tableName."\")' />
					";
				}
			?>
		</form>
	</div>

</body>

</html>