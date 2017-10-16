<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 04.08.2017
 * Time: 10:42
 */

include("scripts/connect.php");
include("scripts/helpers.php");
include("layouts/menu.php");
include("layouts/footer.php");

/* SEF-Links Processor */

if(substr($_SERVER['REQUEST_URI'], 1, 5) == "blog/") {
	$address = substr($_SERVER['REQUEST_URI'], 6);
} else {
	$url = explode("/", substr($_SERVER['REQUEST_URI'], 1));
	$address = $url[1];
}

$linkCheckResult = $mysqli->query("SELECT * FROM subcategories WHERE sef_link = '" . $address . "'");
$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

if ($linkCheck[0] > 0) {
	$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE sef_link = '" . $address . "'");
	$gallery = $galleryResult->fetch_assoc();

	$type = "gallery";
} else {
	$linkCheckResult = $mysqli->query("SELECT * FROM blog_subcategories WHERE sef_link = '" . $address . "'");
	$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

	if($linkCheck[0] > 0) {
		$galleryResult = $mysqli->query("SELECT * FROM blog_subcategories WHERE sef_link = '" . $address . "'");
		$gallery = $galleryResult->fetch_assoc();

		$type = "blog";
	} else {
		$linkCheckResult = $mysqli->query("SELECT * FROM posts WHERE sef_link = '" . $address . "'");
		$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

		if($linkCheck[0] > 0) {
			$galleryResult = $mysqli->query("SELECT * FROM posts WHERE sef_link = '" . $address . "'");
			$gallery = $galleryResult->fetch_assoc();

			$type = "post";
		} else {
			if(substr($_SERVER['REQUEST_URI'], 0, 5) == "/tag/") {
				$tagResult = $mysqli->query("SELECT * FROM tags WHERE name = '".urldecode(substr($_SERVER['REQUEST_URI'], 5))."'");
				$tag = $tagResult->fetch_assoc();

				$postList = array();

				$postsResult = $mysqli->query("SELECT * FROM posts_tags WHERE tag_id = '".$tag['id']."' ORDER BY id DESC");
				while($posts = $postsResult->fetch_assoc()) {
					$postCheckResult = $mysqli->query("SELECT draft FROM posts WHERE id = '".$posts['post_id']."'");
					$postCheck = $postCheckResult->fetch_array(MYSQLI_NUM);

					if($postCheck[0] == 0) {
						array_push($postList, $posts['post_id']);
					}
				}

				$type = "tag";
			} else {
				header("Location: /");
			}
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

	<?php
		$settingsResult = $mysqli->query("SELECT * FROM settings WHERE id = '1'");
		$settings = $settingsResult->fetch_assoc();
	?>

	<meta charset="utf-8" />

	<title><?= $gallery['title'] ?></title>

	<meta name="description" content="<?= $gallery['description'] ?>" />
	<meta name="keywords" content="<?= $gallery['keywords'] ?>" />
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
	<link rel="stylesheet" type="text/css" href="/plugins/lightview/css/lightview/lightview.css" />

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>
	<script type="text/javascript" src="/plugins/lightview/js/lightview/lightview.js"></script>
	<script type="text/javascript" src="/js/common.js"></script>
	<script type="text/javascript" src="/js/notify.js"></script>
	<script type="text/javascript" src="/js/share.js"></script>
	<script type="text/javascript" src="/js/gallery.js"></script>

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

	<?php
		if($type != "post") {
			echo showMenu_2ndLevel(null, $categories, $settings);
		} else {
			echo showMenu_3rdLevel(null, $categories, $settings);
		}
	?>

	<?php
		/* Правило отображения контента для галереи */
		if($type == "gallery") {
			echo "
				<div id='slider'>
					<img src='/img/photos/gallery/main/".$gallery['photo']."' />
				</div>
				<br /><br />
				".$gallery['text']."
				<br /><br />
				<section class='bigSection' style='margin-top: 0;'>
			";

			$photoResult = $mysqli->query("SELECT * FROM photos WHERE post_id = '".$gallery['id']."'");
			while($photo = $photoResult->fetch_assoc()) {
				echo "<div class='blogSmallPhoto1'><img src='/img/photos/gallery/content/".$photo['file']."' class='bigGalleryPhoto' /></div>";
			}

			echo "</section>";
		}

		if($type == "blog") {
			/* Правило отображения контента для блога */
			echo "
				<br /><br />
				<section style='text-align: center;'>
			";

			$tResult = $mysqli->query("SELECT DISTINCT(tag_id) FROM posts_tags");
			$i = 1;
			$tags = array();

			while($t = $tResult->fetch_array(MYSQLI_NUM)) {
				$show = 0;
				$postIDResult = $mysqli->query("SELECT * FROM posts_tags WHERE tag_id = '".$t[0]."'");
				while($postID = $postIDResult->fetch_assoc()) {
					$draftResult = $mysqli->query("SELECT draft FROM posts WHERE id = '".$postID['post_id']."'");
					$draft = $draftResult->fetch_array(MYSQLI_NUM);

					if($draft[0] == 0) {
						$show++;
					}
				}

				if($show > 0) {
					$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t[0]."'");
					$tag = $tagResult->fetch_assoc();

					array_push($tags, $tag['name']);
				}

				$i++;
			}

			sort($tags, SORT_STRING);
			$i = 0;

			foreach ($tags as $tag) {
				echo "<a href='/tag/".$tag."'><span class='tagFont'>".$tag."</span></a>";

				if($i < count($tags)) {
					echo "&nbsp;&nbsp;&nbsp;";
				}

				$i++;
			}

			echo "<br /><br /><br /><br />";

			$postResult = $mysqli->query("SELECT * FROM posts WHERE subcategory_id = '".$gallery['id']."' AND draft = '0' ORDER BY date DESC");
			while ($post = $postResult->fetch_assoc()) {
				$likesCountResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$post['id']."'");
				$likesCount = $likesCountResult->fetch_array(MYSQLI_NUM);

				if($likesCount[0] == 0) {
					$likes = "";
				} else {
					$likes = $likesCount[0];
				}

				$likedResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$post['id']."' AND ip = '".$_SERVER['REMOTE_ADDR']."'");
				$liked = $likedResult->fetch_array(MYSQLI_NUM);

				$commentsCountResult = $mysqli->query("SELECT COUNT(id) FROM comments WHERE post_id = '".$post['id']."'");
				$commentsCount = $commentsCountResult->fetch_array(MYSQLI_NUM);

				if($commentsCount[0] == 0) {
					$comments = "";
				} else {
					$comments = $commentsCount[0];
				}

				echo "
					<div class='sectionHeader'>
						<div class='blogLine'></div>
						<div class='blogDate'>".formatDate($post['date'])."</div>
						<div class='blogLine'></div>
					</div>
					<br />
					<div class='postHeader'><a href='/blog/".$post['sef_link']."'>".$post['name']."</a></div>
					<br /><br />
					<div class='blogDescription'><p>".$post['description']."</p></div>
					<img src='/img/photos/blog/main/".$post['photo']."' class='blogMainPhoto' />
					<div class='sectionHeader'><a href='/blog/".$post['sef_link']."'><span class='openFont'>Смотреть далее <i class='fa fa-angle-double-right' aria-hidden='true'></i></span></a></div>
					<br /><br />
					<div class='separator'></div>
					<br />
					<div class='sectionHeader'>
						<div class='blogButtons'>
							<div class='shareButtonBlock'><span id='like".$post['id']."' class='like'"; if($liked[0] > 0) {echo "style='cursor: default; color: #b21c1c;'";} echo "><i class='fa fa-heart-o' aria-hidden='true' "; if($liked[0] == 0) {echo "onclick='likePost(\"".$post['id']."\")'";} echo "></i> <span id='likesCount".$post['id']."' class='likesCount'>".$likes."</span></span></div>
							<div class='shareButtonBlock'><a href='/blog/".$post['sef_link']."#comments'><span class='blogButton'><i class='fa fa-comment-o' aria-hidden='true'></i> ".$comments."</span></a></div>
							<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'><i class='fa fa-share-square-o' aria-hidden='true'></i></div>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
									<div class='shareBlock' id='shareBlock".$post['id']."'>
										<script src='//yastatic.net/es5-shims/0.0.2/es5-shims.min.js'></script>
										<script src='//yastatic.net/share2/share.js'></script>
										<div class='ya-share2' data-services='vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram' data-size='s' data-title='".$post['name']."' data-url='https://korsart.by/blog/".$post['sef_link']."' data-image='https://korsart.by/img/photos/blog/main/".$post['photo']."' data-direction='vertical'></div>
									</div>
								</div>
							</div>
							<div style='clear: both;'></div>
						</div>
						<div class='blogTags'>
				";
				$tags = array();
				$i = 0;
				$tResult = $mysqli->query("SELECT * FROM posts_tags WHERE post_id = '".$post['id']."'");
				while($t = $tResult->fetch_assoc()) {
					$i++;

					$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t['tag_id']."'");
					$tag = $tagResult->fetch_assoc();

					array_push($tags, $tag['name']);
				}

				sort($tags, SORT_STRING);
				$i = 0;

				foreach ($tags as $tag) {
					echo "<a href='/tag/".$tag."'><span class='postTagFont'>".$tag."</span></a>";

					if($i < (count($tags) - 1)) {
						echo ",";
					}

					if($i < count($tags)) {
						echo "&nbsp;&nbsp;";
					}

					$i++;
				}

				echo "
						</div>
						<div style='clear: both;'></div>
					</div>
				";
			}

			echo "</section>";
		}

		if($type == "post") {
			/* Правило отображеня контента для записи */

			$likesCountResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$gallery['id']."'");
			$likesCount = $likesCountResult->fetch_array(MYSQLI_NUM);

			if($likesCount[0] == 0) {
				$likes = "";
			} else {
				$likes = $likesCount[0];
			}

			$likedResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$gallery['id']."' AND ip = '".$_SERVER['REMOTE_ADDR']."'");
			$liked = $likedResult->fetch_array(MYSQLI_NUM);

			echo "
				<br /><br />
				<section style='text-align: center;'>
			";

			$tResult = $mysqli->query("SELECT DISTINCT(tag_id) FROM posts_tags");
			$i = 1;
			$tags = array();

			while($t = $tResult->fetch_array(MYSQLI_NUM)) {
				$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t[0]."'");
				$tag = $tagResult->fetch_assoc();

				array_push($tags, $tag['name']);

				$i++;
			}

			sort($tags, SORT_STRING);
			$i = 0;

			foreach ($tags as $tag) {
				echo "<a href='/tag/".$tag."'><span class='tagFont'>".$tag."</span></a>";

				if($i < count($tags)) {
					echo "&nbsp;&nbsp;&nbsp;";
				}

				$i++;
			}

			echo "
					<br /><br /><br /><br />
				</section>
				
				<section class='bigSection'>
					<div class='sectionHeader'>
						<div class='blogLine'></div>
						<div class='blogDate'>".formatDate($gallery['date'])."</div>
						<div class='blogLine'></div>
					</div>
					<br />
					<div class='postHeader'><a href='/blog/".$gallery['sef_link']."'>".$gallery['name']."</a></div>
					<br /><br />
					<div class='blogDescription'><p>".$gallery['description']."</p></div>
					<img src='/img/photos/blog/main/".$gallery['photo']."' class='blogMainPhoto' />
					<br />
					<span class='blogFont'>".$gallery['text']."</span>
					<br /><br />
				</section>
				<section class='bigSection' style='text-align: center;'>
					";

			$photoResult = $mysqli->query("SELECT * FROM blog_photos WHERE post_id = '".$gallery['id']."'");
			while($photo = $photoResult->fetch_assoc()) {
				echo "<a href='/img/photos/blog/content/".$photo['file']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='blog'><img src='/img/photos/blog/content/".$photo['file']."' class='blogSmallPhoto".$gallery['style']."' /></a>";

				if($gallery['style'] == 1 and !empty($gallery['description'])) {
					echo "
						<div class='photoDescription'>
							<p>".$photo['description']."</p>
							<br />
						</div>
					";
				}
			}

			echo "
					<br /><br /><br />
					<div class='separator'></div>
					<br /><br />
					<div class='sectionHeader lightFont' style='text-align: center;'>
						<div id='shareButtonsContainer'>
							<div class='likeContainer'>
								<span id='like".$gallery['id']."' class='like'"; if($liked[0] > 0) {echo "style='cursor: default; color: #b21c1c;'";} echo "><i class='fa fa-heart-o' aria-hidden='true' "; if($liked[0] == 0) {echo "onclick='likePost(\"".$gallery['id']."\")'";} echo "></i> <span id='likesCount".$gallery['id']."' class='likesCount'>".$likes."</span></span>
								&nbsp;&nbsp; | &nbsp;&nbsp;
							</div>
							<div class='social'>
								<script src='//yastatic.net/es5-shims/0.0.2/es5-shims.min.js'></script>
								<script src='//yastatic.net/share2/share.js'></script>
								<div class='ya-share2' data-services='vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram' data-size='s' data-title='".$gallery['name']."' data-url='https://korsart.by/blog/".$gallery['sef_link']."' data-image='https://korsart.by/img/photos/blog/main/".$gallery['photo']."'></div>
							</div>
							<div style='clear: both;'></div>
						</div>
					</div>
					<br /><br />
					<div class='sectionHeader'>
			";

			$prevCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts WHERE subcategory_id = '".$gallery['subcategory_id']."' AND date < '".$gallery['date']."'");
			$prevCheck = $prevCheckResult->fetch_array(MYSQLI_NUM);

			if($prevCheck[0] > 0) {
				$prevResult = $mysqli->query("SELECT * FROM posts WHERE subcategory_id = '".$gallery['subcategory_id']."' AND date < '".$gallery['date']."' ORDER BY date DESC LIMIT 1");
				$prev = $prevResult->fetch_assoc();
			}

			$nextCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts WHERE subcategory_id = '".$gallery['subcategory_id']."' AND date > '".$gallery['date']."'");
			$nextCheck = $nextCheckResult->fetch_array(MYSQLI_NUM);

			if($nextCheck[0] > 0) {
				$nextResult = $mysqli->query("SELECT * FROM posts WHERE subcategory_id = '".$gallery['subcategory_id']."' AND date > '".$gallery['date']."' ORDER BY date ASC LIMIT 1");
				$next = $nextResult->fetch_assoc();
			}

			if($prevResult->num_rows > 0) {
		echo "<a href='/blog/".$prev['sef_link']."' style='color: #4c4c4c;' onmouseover='fontColor(\"prevIcon\", \"prevText\", 1)' onmouseout = 'fontColor(\"prevIcon\", \"prevText\", 0)'><i class='fa fa-angle-double-left' aria-hidden='true' id='prevIcon'></i> <span class='directionButton' id='prevText'>Назад</span></a>";

				if($nextResult->num_rows > 0) {
					echo "&nbsp;&nbsp; <span class='lightFont'>|</span> &nbsp;&nbsp;";
				}
			}

			if($nextResult->num_rows > 0) {
			echo "<a href='/blog/".$next['sef_link']."' onmouseover='fontColor(\"nextIcon\", \"nextText\", 1)' onmouseout = 'fontColor(\"nextIcon\", \"nextText\", 0)'><span class='directionButton' id='nextText'>Вперед</span> <i class='fa fa-angle-double-right' aria-hidden='true' id='nextIcon'></i></a>";
			}

			echo "
					</div>
					<br />
					<div class='separator'></div>
					<br /><br />
				</section>
				<section>
					<div class='sectionHeader' id='commentsContainer'>
					<a name='comments'></a>
			";

			$commentResult = $mysqli->query("SELECT * FROM comments WHERE post_id = '".$gallery['id']."' ORDER BY date ASC");
			while($comment = $commentResult->fetch_assoc()) {
				echo "
					<div class='commentBlock'>
						<strong><span class='blogFont'>" .$comment['name']."</strong>, </span><span class='blogFont'>".dateForComment($comment['date'])."</span>
						<br /><br />
						<p class='blogFont'>".$comment['text']."</p>	
					</div>
				";
			}

			echo "
					</div>
					<br /><br />
					<form method='post' id='commentForm'>
						<input id='nameInput' name='name' placeholder='Имя...' />
						<br /><br />
						<textarea id='textInput' name='text' placeholder='Текст...'></textarea>
						<br /><br />
						<div class='sectionHeader' style='text-align: center;'><div class='g-recaptcha' data-sitekey='6LefJi4UAAAAAIZPE_vNxKwYSrZMDmroAoVxQ4DP' style='display: inline-block;'></div></div>
						<br /><br />
						<input type='button' value='Оставить комментарий' id='commentSubmit' onclick='leaveComment(\"".$gallery['id']."\")' />
					</form>
				</section>
			";
		}

		if($type == "tag") {
			/* Правило отображеня контента для тега */

			echo "
				<br /><br />
				<section style='text-align: center;'>
			";

			$tResult = $mysqli->query("SELECT DISTINCT(tag_id) FROM posts_tags");
			$i = 1;
			$tags = array();

			while($t = $tResult->fetch_array(MYSQLI_NUM)) {
				$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t[0]."'");
				$tag = $tagResult->fetch_assoc();

				array_push($tags, $tag['name']);

				$i++;
			}

			sort($tags, SORT_STRING);
			$i = 0;

			foreach ($tags as $tag) {
				if(urldecode(substr($_SERVER['REQUEST_URI'], 5)) != $tag) {
					echo "<a href='/tag/".$tag."'><span class='tagFont'>".$tag."</span></a>";
				} else {
					echo "<span class='tagFont' style='color: #e0c1ac;'>".$tag."</span>";
				}

				if($i < count($tags)) {
					echo "&nbsp;&nbsp;&nbsp;";
				}

				$i++;
			}

			echo "<br /><br /><br /><br />";

			foreach ($postList as $p) {
				$postResult = $mysqli->query("SELECT * FROM posts WHERE id = '".$p."' AND draft = '0'");
				$post = $postResult->fetch_assoc();

				$likesCountResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$p."'");
				$likesCount = $likesCountResult->fetch_array(MYSQLI_NUM);

				if($likesCount[0] == 0) {
					$likes = "";
				} else {
					$likes = $likesCount[0];
				}

				$likedResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$p."' AND ip = '".$_SERVER['REMOTE_ADDR']."'");
				$liked = $likedResult->fetch_array(MYSQLI_NUM);

				$commentsCountResult = $mysqli->query("SELECT COUNT(id) FROM comments WHERE post_id = '".$p."'");
				$commentsCount = $commentsCountResult->fetch_array(MYSQLI_NUM);

				if($commentsCount[0] == 0) {
					$comments = "";
				} else {
					$comments = $commentsCount[0];
				}

				echo "
					<div class='sectionHeader'>
						<div class='blogLine'></div>
	<div class='blogDate'>".formatDate($post['date'])."</div>
						<div class='blogLine'></div>
					</div>
					<br />
					<div class='postHeader'><a href='/blog/".$post['sef_link']."'>".$post['name']."</a></div>
					<br /><br />
					<div class='blogDescription'><p>".$post['description']."</p></div>
					<img src='/img/photos/blog/main/".$post['photo']."' class='blogMainPhoto' />
					<div class='sectionHeader'><a href='/blog/".$post['sef_link']."'><span class='openFont'>Смотреть далее <i class='fa fa-angle-double-right' aria-hidden='true'></i></span></a></div>
					<br /><br />
					<div class='separator'></div>
					<br />
					<div class='sectionHeader'>
						<div class='blogButtons'>
							<div class='shareButtonBlock'><span id='like".$post['id']."' class='like'"; if($liked[0] > 0) {echo "style='cursor: default; color: #b21c1c;'";} echo "><i class='fa fa-heart-o' aria-hidden='true' "; if($liked[0] == 0) {echo "onclick='likePost(\"".$post['id']."\")'";} echo "></i> <span id='likesCount".$post['id']."' class='likesCount'>".$likes."</span></span></div>
							<div class='shareButtonBlock'><a href='/blog/".$post['sef_link']."#comments'><span class='blogButton'><i class='fa fa-comment-o' aria-hidden='true'></i> ".$comments."</span></a></div>
							<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'><i class='fa fa-share-square-o' aria-hidden='true'></i></div>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
									<div class='shareBlock' id='shareBlock".$post['id']."'>
										<script src='//yastatic.net/es5-shims/0.0.2/es5-shims.min.js'></script>
										<script src='//yastatic.net/share2/share.js'></script>
										<div class='ya-share2' data-services='vkontakte,facebook,odnoklassniki,twitter,viber,whatsapp,telegram' data-size='s' data-title='".$post['name']."' data-url='https://korsart.by/blog/".$post['sef_link']."' data-image='https://korsart.by/img/photos/blog/main/".$post['photo']."' data-direction='vertical'></div>
									</div>
								</div>
							</div>
							<div style='clear: both;'></div>
						</div>
						<div class='blogTags'>
				";

				$tags = array();
				$i = 0;
				$tResult = $mysqli->query("SELECT * FROM posts_tags WHERE post_id = '".$post['id']."'");
				while($t = $tResult->fetch_assoc()) {
					$i++;

					$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t['tag_id']."'");
					$tag = $tagResult->fetch_assoc();

					array_push($tags, $tag['name']);
				}

				sort($tags, SORT_STRING);
				$i = 0;

				foreach ($tags as $tag) {
					if(urldecode(substr($_SERVER['REQUEST_URI'], 5)) == $tag) {
						echo "<span class='postTagFont' style='color: #e0c1ac;'>".$tag."</span>";
					} else {
						echo "<a href='/tag/".$tag."'><span class='postTagFont'>".$tag."</span></a>";
					}

					if($i < (count($tags) - 1)) {
						echo ",";
					}

					if($i < count($tags)) {
						echo "&nbsp;&nbsp;";
					}

					$i++;
				}

				echo "
						</div>
						<div style='clear: both;'></div>
					</div>
				";
			}
		}
	?>

	<div onclick="scrollToTop()" id="scroll"><i class="fa fa-chevron-up" aria-hidden="true"></i></div>

	<?= showFooter() ?>

	<script type="text/javascript" src="/js/main-slider.js"></script>
	<script type="text/javascript" src="/js/gallery-slider.js"></script>

</body>

</html>