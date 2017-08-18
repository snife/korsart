<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 15:05
 */

session_start();
include("../../scripts/connect.php");

if($_SESSION['userID'] != 1) {
	header("Location: ../../");
}

if(!empty($_REQUEST['id'])) {
	$subcategoryCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE id = '".$mysqli->real_escape_string($_REQUEST['id'])."'");
	$subcategoryCheck = $subcategoryCheckResult->fetch_array(MYSQLI_NUM);

	if($subcategoryCheck[0] == 0) {
		header("Location: subcategory.php");
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

	<title>Панель администрирования | Добавление раздела в блог</title>

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
	<script type="text/javascript" src="/js/admin/blog/subcategory.js"></script>

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
		<span class="headerFont">Добавление раздела в блоге</span>
		<br /><br />
		<?php
			if(empty($_REQUEST['id'])) {
				$priorityResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories");
				$priority = $priorityResult->fetch_array(MYSQLI_NUM);

				echo "
					<form method='post'>
						<label for='nameInput'>Название:</label>
						<br />
						<input id='nameInput' name='name' />
						<br /><br />
						<label for='linkInput'>Адрес ссылки:</label>
						<br />
						<input id='linkInput' name='link' />
						<br /><br />
						<label for='prioritySelect'>Порядок следования:</label>
						<br />
						<select id='prioritySelect' name='priority'>
				";

				for($i = 1; $i <= ($priority[0] + 1); $i++) {
					echo "<option value='".$i."'"; if($i == ($priority[0] + 1)) {echo " selected";} echo ">".$i."</option>";
				}

				echo "
						</select>
						<br /><br />
						<label for='titleInput'>Заголовок:</label>
						<br />
						<input id='titleInput' name='title' />
						<br /><br />
						<label for='keywordsInput'>Ключевые слова (не обязательно):</label>
						<br />
						<input id='keywordsInput' name='keywords'>
						<br /><br />
						<label for='descriptionInput'>Описание (не обязательно):</label>
						<br />
						<textarea id='descriptionInput' name='description' onkeypress='textAreaHeight(this)'></textarea>
						<br /><br />
						<input type='button' id='subcategorySubmit' value='Добавить' onmouseover='buttonHover(\"subcategorySubmit\", 1)' onmouseout='buttonHover(\"subcategorySubmit\", 0)' class='button' onclick='addSubcategory()' />
					</form>
				";
			} else {

			}
		?>
	</div>

	<script type="text/javascript">
		CKEDITOR.replace("text");
	</script>

</body>

</html>