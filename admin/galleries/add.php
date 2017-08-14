<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 14.08.2017
 * Time: 11:47
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
	header("Location: ../../");
}

if(!empty($_REQUEST['c'])) {
	$categoryCheckResult = $mysqli->query("SELECT COUNT(id) FROM categories WHERE id = '".$mysqli->real_escape_string($_REQUEST['c'])."' AND for_gallery = '1'");
	$categoryCheck = $categoryCheckResult->fetch_array(MYSQLI_NUM);

	if($categoryCheck[0] == 0) {
		header("Location: index.php");
	}
} else {
	header("Location: index.php");
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

	<title>Панель администрирования | Добавление галереи</title>

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
	<script type="text/javascript" src="/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/js/admin/galleries/add.js"></script>

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
			<div class="menuPoint">
				<i class="fa fa-commenting" aria-hidden="true"></i><span> Отзывы</span>
			</div>
		</a>
		<a href="/admin/galleries/">
			<div class="menuPoint active">
				<i class="fa fa-camera" aria-hidden="true"></i><span> Галереи</span>
			</div>
		</a>
		<a href="/admin/blog/">
			<div class="menuPoint">
				<i class="fa fa-bullhorn" aria-hidden="true"></i><span> Блог</span>
			</div>
		</a>
	</div>

	<div id="content">
		<?php
			$categoryResult = $mysqli->query("SELECT * FROM categories WHERE id = '".$mysqli->real_escape_string($_REQUEST['c'])."'");
			$category = $categoryResult->fetch_assoc();

			$subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE category_id = '".$category['id']."'");
			$subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);
		?>
		<span class="headerFont">Добавление галереи в раздел &laquo;<span style="color: #e0c1ac;"><?= $category['name'] ?></span>&raquo;</span>
		<br /><br />
		<form method="post" enctype="multipart/form-data" id="addForm">
			<label for="nameInput">Название:</label>
			<br />
			<input id="nameInput" name="name" />
			<br /><br />
			<label for="sefLinkInput">Адрес ссылки:</label>
			<br />
			<input id="sefLinkInput" name="sefLink" />
			<br /><br />
			<label for="prioritySelect">Порядок следования:</label>
			<br />
			<select id="prioritySelect" name="priority">
				<?php
					for($i = 0; $i <= $subcategoriesCount[0]; $i++) {
						echo "<option value='".($i + 1)."'"; if($i == $subcategoriesCount[0]) {echo " selected";} echo ">".($i + 1)."</option>";
					}
				?>
			</select>
			<br /><br />
			<label for="titleInput">Заголовок:</label>
			<br />
			<input id="titleInput" name="title" />
			<br /><br />
			<label for="keywordsInput">Ключевые слова (не обязательно):</label>
			<br />
			<input id="keywordsInput" name="keywords" />
			<br /><br />
			<label for="descriptionInput">Описание (не обязательно):</label>
			<br />
			<textarea id="descriptionInput" name="description" onkeydown="textAreaHeight(this)"></textarea>
			<br /><br />
			<label for="photoInput">Заглавное фото:</label>
			<br />
			<input type="file" id="photoInput" name="photo" style="padding-top: 10px;" />
			<br /><br />
			<label for="textInput">Текст:</label>
			<br />
			<textarea id="textInput" name="text"></textarea>
			<br /><br />
			<label for="photosInput">Фотографии галереи:</label>
			<br />
			<input type="file" id="photoInput" name="photos[]" style="padding-top: 10px;" multiple />
			<br /><br />
			<input type="button" id="gallerySubmit" value="Добавить" onmouseover="buttonHover('gallerySubmit', 1)" onmouseout="buttonHover('gallerySubmit', 0)" class="button" onclick="addGallery('<?= $_REQUEST['c'] ?>')" />
		</form>
	</div>

	<script type="text/javascript">
		CKEDITOR.replace("text");
	</script>

</body>

</html>