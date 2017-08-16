<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 14.08.2017
 * Time: 11:08
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

	<title>Панель администрирования | Галереи</title>

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
	<script type="text/javascript" src="/js/admin/galleries/index.js"></script>

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
		<span class="headerFont">Галереи</span>
		<br /><br />
		<form method="post">
			<label for="categorySelect">Выберите раздел:</label>
			<br />
			<select id="categorySelect" name="category" onchange="window.location = '?c=' + this.options[this.selectedIndex].value">
			<option value="">- Выберите раздел -</option>
				<?php
					$categoryResult = $mysqli->query("SELECT * FROM categories WHERE for_gallery = '1'");
					while ($category = $categoryResult->fetch_assoc()) {
						echo "<option value='".$category['id']."'"; if($_REQUEST['c'] == $category['id']) {echo " selected";} echo ">".$category['name']."</option>";
					}
				?>
			</select>
			<?php
				if(!empty($_REQUEST['c'])) {
					echo "
						<br /><br />
						<input type='button' id='gallerySubmit' value='Новая галерея' onmouseover='buttonHover(\"gallerySubmit\", 1)' onmouseout='buttonHover(\"gallerySubmit\", 0)' onclick='window.location.href = \"/admin/galleries/add.php?c=".$_REQUEST['c']."\"' class='button' />
					";
				}
			?>
		</form>
		<?php
			if(!empty($_REQUEST['c'])) {
				$categoryResult = $mysqli->query("SELECT * FROM categories WHERE id = '".$mysqli->real_escape_string($_REQUEST['c'])."'");
				$category = $categoryResult->fetch_assoc();

				echo "
					<br /><br />
					<h3>Список галерей в разделе &laquo;<span style='color: #e0c1ac;'>".$category['name']."</span>&raquo;</h3>
					<div class='subcategory'></div>
				";

				$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$mysqli->real_escape_string($_REQUEST['c'])."' ORDER BY priority");
				while($gallery = $galleryResult->fetch_assoc()) {
					echo "
						<div class='subcategory'>
							<a href='/".$gallery['sef_link']."' target='_blank'><div class='subcategoryName'>".$gallery['name']."</div></a>
							<div class='subcategoryButtons'>
								<a href='index.php?c=".$_REQUEST['c']."&id=".$gallery['id']."'><span><i class='fa fa-pencil-square' aria-hidden='true'></i> Редактировать</span></a>
								<br />
								<span onclick='deleteGallery(\"".$gallery['id']."\")'><i class='fa fa-trash' aria-hidden='true'></i> Удалить</span>
							</div>
						</div>
					";
				}
			}
		?>
	</div>

</body>

</html>