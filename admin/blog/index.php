<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 13:55
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
	header("Location: ../../");
}

if(!empty($_REQUEST['c'])) {
	$categoryCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE id = '".$mysqli->real_escape_string($_REQUEST['c'])."'");
	$categoryCheck = $categoryCheckResult->fetch_array(MYSQLI_NUM);

	if($categoryCheck[0] == 0) {
		header("Location: index.php");
	}
}

if(!empty($_REQUEST['id'])) {
	$galleryCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
	$galleryCheck = $galleryCheckResult->fetch_array(MYSQLI_NUM);

	if($galleryCheck[0] == 0) {
		if(empty($_REQUEST['c'])) {
			header("Location: index.php");
		} else {
			header("Location: index.php?c=".$_REQUEST['c']);
		}
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

	<title>Панель администрирования | Блог</title>

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
	<script type="text/javascript" src="/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/plugins/lightview/js/lightview/lightview.js"></script>
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/js/admin/blog/index.js"></script>

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
			<div class="menuPoint">
				<i class="fa fa-camera" aria-hidden="true"></i><span> Галереи</span>
			</div>
		</a>
		<a href="/admin/blog/">
			<div class="menuPoint active">
				<i class="fa fa-bullhorn" aria-hidden="true"></i><span> Блог</span>
			</div>
		</a>
	</div>

	<div id="content">
		<span class="headerFont">Блог</span>
		<br /><br />
		<?php
			if(empty($_REQUEST['id'])) {
				echo "
					<form method='post'>
						<label for='categorySelect'>Выберите раздел:</label>
						<br />
						<select id='categorySelect' name='category' onchange=\"window.location = '?c=' + this.options[this.selectedIndex].value\">
							<option value=''>- Выберите раздел -</option>
				";

				$categoryResult = $mysqli->query("SELECT * FROM blog_subcategories ORDER BY priority");

				while ($category = $categoryResult->fetch_assoc()) {
					echo "<option value='".$category['id']."'"; if($_REQUEST['c'] == $category['id']) {echo " selected";} echo ">".$category['name']."</option>";
				}

				echo "
					</select>
					<br /><br />
					<input type='button' id='gallerySubmit' value='Новый раздел' onmouseover='buttonHover(\"gallerySubmit\", 1)' onmouseout='buttonHover(\"gallerySubmit\", 0)' onclick='window.location.href = \"/admin/blog/subcategory.php\"' class='button' />
				";
			}

			/*
			if(empty($_REQUEST['id'])) {
				echo "
					<form method='post'>
						<label for='categorySelect'>Выберите раздел:</label>
						<br />
						<select id='categorySelect' name='category' onchange=\"window.location = '?c=' + this.options[this.selectedIndex].value\">
							<option value=''>- Выберите раздел -</option>
				";

				$categoryResult = $mysqli->query("SELECT * FROM categories WHERE for_gallery = '1'");

				while ($category = $categoryResult->fetch_assoc()) {
					echo "<option value='".$category['id']."'"; if($_REQUEST['c'] == $category['id']) {echo " selected";} echo ">".$category['name']."</option>";
				}

				echo "</select>";
			}

			if(!empty($_REQUEST['c'] and empty($_REQUEST['id']))) {
				echo "
						<br /><br />
						<input type='button' id='gallerySubmit' value='Новая галерея' onmouseover='buttonHover(\"gallerySubmit\", 1)' onmouseout='buttonHover(\"gallerySubmit\", 0)' onclick='window.location.href = \"/admin/galleries/add.php?c=".$_REQUEST['c']."\"' class='button' />
					";
			}

			echo "</form>";

			if(!empty($_REQUEST['c'])) {
				if(empty($_REQUEST['id'])) {
					$categoryResult = $mysqli->query("SELECT * FROM categories WHERE id = '".$mysqli->real_escape_string($_REQUEST['c'])."'");
					$category = $categoryResult->fetch_assoc();

					echo "
						<br /><br />
						<h3>Список галерей в разделе &laquo;<span style='color: #e0c1ac;'>".$category['name']."</span>&raquo;</h3>
						<div class='subcategory'></div>
						<div id='galleryTable'>
					";

					$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$mysqli->real_escape_string($_REQUEST['c'])."' ORDER BY priority");
					while($gallery = $galleryResult->fetch_assoc()) {
						echo "
							<div class='subcategory'>
								<a href='/".$gallery['sef_link']."' target='_blank'><div class='subcategoryName'>".$gallery['name']."</div></a>
								<div class='subcategoryButtons'>
									<a href='index.php?c=".$_REQUEST['c']."&id=".$gallery['id']."'><span><i class='fa fa-pencil-square' aria-hidden='true'></i> Редактировать</span></a>
									<br />
									<span onclick='deleteGallery(\"".$gallery['id']."\", \"".$gallery['category_id']."\")'><i class='fa fa-trash' aria-hidden='true'></i> Удалить</span>
								</div>
							</div>
						";
					}

					echo "</div>";
				} else {
					//Редактирование галереи

					$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
					$gallery = $galleryResult->fetch_assoc();

					$priorityResult = $mysqli->query("SELECT MAX(priority) FROM subcategories WHERE category_id = '".$mysqli->real_escape_string($_REQUEST['c'])."'");
					$priority = $priorityResult->fetch_array(MYSQLI_NUM);

					echo "
						<form method='post' enctype='multipart/form-data' id='editForm' name='editForm'>
							<label for='nameInput'>Название:</label>
							<br />
							<input id='nameInput' name='name' value='".$gallery['name']."' />
							<br /><br />
							<label for='linkInput'>Адрес ссылки:</label>
							<br />
							<input id='linkInput' name='link' value='".$gallery['sef_link']."' />
							<br /><br />
							<label for='prioritySelect'>Порядок следлвания:</label>
							<br />
							<select id='prioritySelect' name='priority'>
					";

					for($i = 1; $i <= $priority[0]; $i++) {
						echo "<option value='".$i."'"; if($i == $gallery['priority']) {echo " selected";} echo ">".$i."</option>";
					}

					echo "
							</select>
							<br /><br />
							<label for='titleInput'>Заголовок:</label>
							<br />
							<input id='titleInput' name='title' value='".$gallery['title']."' />
							<br /><br />
							<label for='keywordsInput'>Ключевые слова (не обязательно):</label>
							<br />
							<input id='keywordsInput' name='keywords' value='".$gallery['keywords']."'>
							<br /><br />
							<label for='descriptionInput'>Описание (не обязательно):</label>
							<br />
							<input id='descriptionInput' name='description' value='".$gallery['description']."' onkeydown='textAreaHeight(this)' />
							<br /><br />
							<label for='photoInput'>Заглавное фото:</label>
							<br />
							<input type='file' id='photoInput' name='photo' class='file' />
							<br /><br />
							<a href='/img/photos/gallery/main/".$gallery['photo']."' class='lightview' data-lightview-options='skin: \"light\"'><span class='adminLinkDashed'>Посмотреть фотографию</span></a>						
							<br /><br />
							<label for='textInput'>Текст:</label>
							<br />
							<textarea id='textInput' name='text'></textarea>
							<br /><br />
							<label for='photosInput'>Добавить фотографии в галерею:</label>
							<br />
							<input type='file' id='photosInput' name='photos[]' class='file' multiple />
					";

					$photosCountResult = $mysqli->query("SELECT COUNT(id) FROM photos WHERE post_id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
					$photosCount = $photosCountResult->fetch_array(MYSQLI_NUM);

					if($photosCount[0] > 0) {
						echo "
							<br /><br />
							<label>Фотографии:</label>
							<br />
							<div id='galleryPhotos'>
						";

						$photoResult = $mysqli->query("SELECT * FROM photos WHERE post_id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
						while($photo = $photoResult->fetch_assoc())
						{
							echo "
								<div class='galleryPhoto'>
									<a href='/img/photos/gallery/content/".$photo['file']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='photos'><img src='/img/photos/gallery/content/".$photo['file']."' /></a>
									<br />
									<span onclick='deleteGalleryPhoto(\"".$photo['id']."\", \"".$_REQUEST['id']."\")' class='adminLink'>Удалить</span>
								</div>
							";
						}

						echo "</div>";
					}

					echo "
							<br /><br />
							<input type='button' id='editSubmit' value='Редактировать' onmouseover='buttonHover(\"editSubmit\", 1)' onmouseout='buttonHover(\"editSubmit\", 0)' onclick='editGallery(\"".$_REQUEST['id']."\")' class='button' />
						</form>
					";
				}
			}
			*/
		?>
	</div>

	<script type="text/javascript">
		CKEDITOR.replace("text");
	</script>

</body>

</html>