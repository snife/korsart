<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 21.08.2017
 * Time: 10:38
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
} else {
	header("Location: index.php");
}

if(!empty($_REQUEST['id'])) {
	$idCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."' AND subcategory_id = '".$mysqli->real_escape_string($_REQUEST['c'])."'");
	$idCheck = $idCheckResult->fetch_array(MYSQLI_NUM);

	if($idCheck[0] == 0) {
		header("Location: post.php?c=".$_REQUEST['c']);
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

	<title>Панель администрирования | <?php if(empty($_REQUEST['id'])) {echo "Добавление записи в блог";} else {echo "Редактирование записи в блоге";} ?></title>

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
	<link rel="stylesheet" href="/plugins/Remodal-1.1.1/dist/remodal.css">
	<link rel="stylesheet" href="/plugins/Remodal-1.1.1/dist/remodal-default-theme.css">

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script type="text/javascript" src="/plugins/ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="/plugins/lightview/js/lightview/lightview.js"></script>
	<script type="text/javascript" src="/plugins/Remodal-1.1.1/dist/remodal.min.js"></script>
	<script type="text/javascript" src="/js/admin/common.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/js/admin/blog/post.js"></script>

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

<body <?php if(!empty($_REQUEST['id'])) {echo "onload='loadText(\"".$_REQUEST['id']."\")'";} ?>>
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
		<span class="headerFont"><?php if(empty($_REQUEST['id'])) {echo "Добавление записи в блог";} else {echo "Редактирование записи в блоге";} ?></span>
		<br /><br />
		<?php
			if(empty($_REQUEST['id'])) {
				//Добавление записи

				echo "
					<form method='post' id='addForm' enctype='multipart/form-data'>
						<label for='nameInput'>Название:</label>
						<br />
						<input id='nameInput' name='name' />
						<br /><br />
						<label for='linkInput'>Адрес ссылки:</label>
						<br />
						<input id='linkInput' name='link' />
						<br /><br />
						<label for='descriptionInput'>Краткое описание:</label>
						<br />
						<textarea id='descriptionInput' name='description' onkeypress='textAreaHeight(this)'></textarea>
						<br /><br />
						<label for='photoInput'>Заглавная фотография:</label>
						<br />
						<input type='file' class='file' id='photoInput' name='photo' />
						<br /><br />
						<label for='textInput'>Текст:</label>
						<br />
						<textarea id='textInput' name='text'></textarea>
						<br /><br />
						<label for='photosInput'>Дополнительные фотографии:</label>
						<br />
						<input type='file' id='photosInput' name='photos[]' class='file' multiple />
						<br /><br />
						<label for='tagsInput'>Теги:</label>
						<br />
						<input id='tagsInput' name='tags' />
						<br /><br />
						<label for='styleSelect'>Стиль размещения фотографий:</label>
						<br />
						<select id='styleSelect' name='style'>
							<option value='1' selected>На всю ширину экрана</option>
							<option value='2'>2 фотографии в ряд</option>
							<option value='3'>3 фотографии в ряд</option>
							<option value='4'>4 фотографии в ряд</option>
						</select>
						<br /><br />
						<label for='draftInput'>Черновик?</label>
						<br />
						<input type='checkbox' id='draftInput' name='draft' />
						<br /><br />
						<input type='button' class='button' id='addSubmit' value='Добавить' onmouseover='buttonHover(\"addSubmit\", 1)' onmouseout='buttonHover(\"addSubmit\", 0)' onclick='addPost(\"".$_REQUEST['c']."\")' />
					</form>
				";
			} else {
				//Редактирование записи

				$postResult = $mysqli->query("SELECT * FROM posts WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
				$post = $postResult->fetch_assoc();

				echo "
					<form method='post' id='editForm' enctype='multipart/form-data'>
						<label for='nameInput'>Название:</label>
						<br />
						<input id='nameInput' name='name' value='".$post['name']."' />
						<br /><br />
						<label for='linkInput'>Адрес ссылки:</label>
						<br />
						<input id='linkInput' name='link' value='".$post['sef_link']."' />
						<br /><br />
						<label for='descriptionInput'>Краткое описание:</label>
						<br />
						<textarea id='descriptionInput' name='description' onkeypress='textAreaHeight(this)'>".str_replace('<br />', '', $post['description'])."</textarea>
						<br /><br />
						<label for='photoInput'>Заглавная фотография:</label>
						<br />
						<input type='file' class='file' id='photoInput' name='photo' />
						<br /><br />
						<a href='/img/photos/blog/main/".$post['photo']."' class='lightview' data-lightview-options='skin: \"light\"'><span class='adminLinkDashed'>Посмортеть фотографию</span></a>
						<br /><br />
						<label for='textInput'>Текст:</label>
						<br />
						<textarea id='textInput' name='text'></textarea>
						<br /><br />
						<label for='photosInput'>Дополнительные фотографии:</label>
						<br />
						<input type='file' id='photosInput' name='photos[]' class='file' multiple />
					";

					$photosCountResult = $mysqli->query("SELECT COUNT(id) FROM blog_photos WHERE post_id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
					$photosCount = $photosCountResult->fetch_array(MYSQLI_NUM);

					if($photosCount[0] > 0) {
						echo "
							<br /><br />
							<label>Фотографии:</label>
							<br />
							<div id='galleryPhotos'>
						";

						$photoResult = $mysqli->query("SELECT * FROM blog_photos WHERE post_id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
						while($photo = $photoResult->fetch_assoc()) {
							echo "
								<div class='galleryPhoto'>
									<a href='/img/photos/blog/content/".$photo['file']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='photos'><img src='/img/photos/blog/content/".$photo['file']."' /></a>
									<br />
									<span onclick='deleteBlogPhoto(\"".$photo['id']."\", \"".$_REQUEST['id']."\")' class='adminLink'>Удалить</span>
									<br />
									<a data-remodal-target='modal'><span class='adminLink' onclick='setModalID(\"".$photo['id']."\")'>Описание</span></a>
								</div>
							";
						}

						echo "</div>";
					}

					$tagResult = $mysqli->query("SELECT * FROM posts_tags WHERE post_id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");

					$tags = "";
					$i = 1;
					$count = $tagResult->num_rows;

					while($tag = $tagResult->fetch_assoc()) {
						$tResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$tag['tag_id']."'");
						$t = $tResult->fetch_assoc();

						$tags .= $t['name'];

						if($i < $count) {
							$tags .= ",";
						}

						$i++;
					}

					echo "
						<br /><br />
						<label for='tagsInput'>Теги:</label>
						<br />
						<input id='tagsInput' name='tags' value='".$tags."' />
						<br /><br />
						<label for='styleSelect'>Стиль размещения фотографий:</label>
						<br />
						<select id='styleSelect' name='style'>
							<option value='1'"; if($post['style'] == 1) {echo " selected";} echo ">На всю ширину экрана</option>
							<option value='2'"; if($post['style'] == 2) {echo " selected";} echo ">2 фотографии в ряд</option>
							<option value='3'"; if($post['style'] == 3) {echo " selected";} echo ">3 фотографии в ряд</option>
							<option value='4'"; if($post['style'] == 4) {echo " selected";} echo ">4 фотографии в ряд</option>
						</select>
						<br /><br />
						<label for='draftInput'>Черновик?</label>
						<br />
						<input type='checkbox' id='draftInput' name='draft'"; if($post['draft'] == 1) {echo " checked";} echo " />
						<br /><br />
						<input type='button' class='button' id='addSubmit' value='Редактировать' onmouseover='buttonHover(\"addSubmit\", 1)' onmouseout='buttonHover(\"addSubmit\", 0)' onclick='editPost(\"".$_REQUEST['id']."\")' />
					</form>
					
					<div class='remodal' data-remodal-id='modal' data-remodal-options='closeOnConfirm: false' onload='alert(1)'>
						<button data-remodal-action='close' class='remodal-close'></button>
						<div style='width: 80%; margin: 0 auto;'><h1>Добавить описание фотографии</h1></div>
						<br />
						<img id='modalPhoto' src='/img/system/spinner.gif' />
							<form method='post' id='modalForm'>
								<input id='idInput' name='id' placeholder='ID фотографии' hidden readonly />
								<br /><br />
								<textarea id='photoDescriptionInput' name='photoDescription' placeholder='Описание'></textarea>
							</form>
						<br />
						<button data-remodal-action='confirm' class='remodal-confirm' style='width: 150px;' onclick='addPhotoDescription()'>Добавить описание</button>
					</div>
				";
			}
		?>
	</div>

	<script type="text/javascript">
		CKEDITOR.replace("text");
	</script>

</body>

</html>