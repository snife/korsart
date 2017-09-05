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

$address = substr($_SERVER['REQUEST_URI'], 1);

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
		if($type != "tag") {
			echo showMenu(null, $categories, $settings);
		} else {
			echo showMenu_2ndLevel(null, $categories, $settings);
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
			";
		}

		if($type == "blog") {
			/* Правило отображения контента для блога */
			echo "
				<br /><br />
				<section style='text-align: center;'>
			";

			$tResult = $mysqli->query("SELECT DISTINCT(tag_id) FROM posts_tags WHERE subcategory_id = '".$gallery['id']."'");
			$i = 1;

			while($t = $tResult->fetch_array(MYSQLI_NUM)) {
				$show = 0;
				$postIDResult = $mysqli->query("SELECT * FROM posts_tags WHERE tag_id = '".$t[0]."' AND subcategory_id = '".$gallery['id']."'");
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

					echo "<a href='/tag/".$tag['name']."'><span class='tagFont'>".$tag['name']."</span></a>";

					if($i < $tResult->num_rows) {
						echo "&nbsp;&nbsp;&nbsp;";
					}
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
					<div class='postHeader'><a href='/".$post['sef_link']."'>".$post['name']."</a></div>
					<br /><br />
					<div class='blogDescription'><p>".$post['description']."</p></div>
					<img src='/img/photos/blog/main/".$post['photo']."' class='blogMainPhoto' />
					<div class='sectionHeader'><a href='/".$post['sef_link']."'><span class='openFont'>Смотреть далее <i class='fa fa-angle-double-right' aria-hidden='true'></i></span></a></div>
					<br /><br />
					<div class='separator'></div>
					<br />
					<div class='sectionHeader'>
						<div class='blogButtons'>
							<div class='shareButtonBlock'><span id='like".$post['id']."' class='like'"; if($liked[0] > 0) {echo "style='cursor: default; color: #b21c1c;'";} echo "><i class='fa fa-heart-o' aria-hidden='true' "; if($liked[0] == 0) {echo "onclick='likePost(\"".$post['id']."\")'";} echo "></i> <span id='likesCount".$post['id']."' class='likesCount'>".$likes."</span></span></div>
							<div class='shareButtonBlock'><a href='/".$post['sef_link']."#comments'><span class='blogButton'><i class='fa fa-comment-o' aria-hidden='true'></i> ".$comments."</span></a></div>
							<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'><i class='fa fa-share-square-o' aria-hidden='true'></i></div>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
									<div class='shareBlock' id='shareBlock".$post['id']."'>
										<div class='shareLine'><div class='shareIcon'><a href='https://vk.com/share.php?url=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."&title=".$post['name']."&description=".$post['description']."&image=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$post['photo']."&noparse=true' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"vk\", 1)' onmouseout='shareButtonColor(\"vk\", 0)'><i class='fa fa-vk' aria-hidden='true' title='Поделиться в VK' id='vk-icon'></i></div><div class='shareName' id='vk-text'>VK</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://www.facebook.com/sharer/sharer.php?s=100&p%5Btitle%5D=".$post['name']."&p%5Bsummary%5D=".$post['description']."&p%5Burl%5D=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."&p%5Bimages%5D%5B0%5D=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$post['photo']."' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"facebook\", 1)' onmouseout='shareButtonColor(\"facebook\", 0)'><i class='fa fa-facebook' aria-hidden='true' title='Поделиться в Facebook' id='facebook-icon'></i></div><div class='shareName' id='facebook-text'>Facebook</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments=".$post['name']."&st._surl=".$_SERVER['HTTP_HOST']."/".$post['photo']."' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"odnoklassniki\", 1)' onmouseout='shareButtonColor(\"odnoklassniki\", 0)'><i class='fa fa-odnoklassniki' aria-hidden='true' title='Поделиться в Одноклассниках' id='odnoklassniki-icon'></i></div><div class='shareName' id='odnoklassniki-text'>Odnoklassniki</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Fkorsart.by%2F_display%2F&text=".$post['name']."&url=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."' target='_blank' onclick='return Share.me(this)' onmouseover='shareButtonColor(\"twitter\", 1)' onmouseout='shareButtonColor(\"twitter\", 0)'><i class='fa fa-twitter' aria-hidden='true' title='Поделиться в Twitter' id='twitter-icon'></i></div><div class='shareName' id='twitter-text'>Twitter</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://connect.mail.ru/share?url=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."&title=".$post['name']."&description=".$post['description']."&imageurl=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$post['photo']."' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"mail-ru\", 1)' onmouseout='shareButtonColor(\"mail-ru\", 0)'><i class='fa fa-at' aria-hidden='true' title='Поделиться в Mail.ru' id='mail-ru-icon'></i></div><div class='shareName' id='mail-ru-text'>Mail.ru</div></a></div>	
									</div>
								</div>
							</div>
							<div style='clear: both;'></div>
						</div>
						<div class='blogTags'>
				";

				$i = 0;
				$tResult = $mysqli->query("SELECT * FROM posts_tags WHERE post_id = '".$post['id']."'");
				while($t = $tResult->fetch_assoc()) {
					$i++;

					$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t['tag_id']."'");
					$tag = $tagResult->fetch_assoc();

					echo "<a href='/tag/".$tag['name']."'><span class='postTagFont'>".$tag['name']."</span></a>";

					if($i < $tResult->num_rows) {
						echo ",&nbsp;&nbsp;";
					}
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

			while($t = $tResult->fetch_array(MYSQLI_NUM)) {
				$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t[0]."'");
				$tag = $tagResult->fetch_assoc();

				echo "<a href='/tag/".$tag['name']."'><span class='tagFont'>".$tag['name']."</span></a>";

				if($i < $tResult->num_rows) {
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
					<div class='postHeader'><a href='/".$gallery['sef_link']."'>".$gallery['name']."</a></div>
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
				echo "<a href='/img/photos/blog/content/".$photo['file']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='blog'><img src='/img/photos/blog/content/".$photo['file']."' class='blogSmallPhoto' /></a>";
			}

			echo "
					<br /><br /><br />
					<div class='separator'></div>
					<br /><br />
					<div class='sectionHeader lightFont'>
						<span id='like".$gallery['id']."' class='like'"; if($liked[0] > 0) {echo "style='cursor: default; color: #b21c1c;'";} echo "><i class='fa fa-heart-o' aria-hidden='true' "; if($liked[0] == 0) {echo "onclick='likePost(\"".$gallery['id']."\")'";} echo "></i> <span id='likesCount".$gallery['id']."' class='likesCount'>".$likes."</span></span>
						&nbsp;&nbsp; | &nbsp;&nbsp;
						<a href='https://vk.com/share.php?url=".$_SERVER['HTTP_HOST']."/".$gallery['sef_link']."&title=".$gallery['name']."&description=".$gallery['description']."&image=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$gallery['photo']."&noparse=true' target='_blank' onclick='return Share.me(this);'><i class='fa fa-vk' aria-hidden='true' title='Поделиться в VK'></i></a>
						&nbsp;&nbsp; | &nbsp;&nbsp;
						<a href='https://www.facebook.com/sharer/sharer.php?s=100&p%5Btitle%5D=".$gallery['name']."&p%5Bsummary%5D=".$gallery['description']."&p%5Burl%5D=".$_SERVER['HTTP_HOST']."/".$gallery['sef_link']."&p%5Bimages%5D%5B0%5D=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$gallery['photo']."' target='_blank' onclick='return Share.me(this);'><i class='fa fa-facebook' aria-hidden='true' title='Поделиться в Facebook'></i></a>
						&nbsp;&nbsp; | &nbsp;&nbsp;
						<a href='https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments=".$gallery['name']."&st._surl=".$_SERVER['HTTP_HOST']."/".$gallery['photo']."' target='_blank' onclick='return Share.me(this);'><i class='fa fa-odnoklassniki' aria-hidden='true' title='Поделиться в Одноклассниках'></i></a>
						&nbsp;&nbsp; | &nbsp;&nbsp;
						<a href='https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Fkorsart.by%2F_display%2F&text=".$gallery['name']."&url=".$_SERVER['HTTP_HOST']."/".$gallery['sef_link']."' target='_blank' onclick='return Share.me(this)'><i class='fa fa-twitter' aria-hidden='true' title='Поделиться в Twitter'></i></a>
						&nbsp;&nbsp; | &nbsp;&nbsp;
						<a href='https://connect.mail.ru/share?url=".$_SERVER['HTTP_HOST']."/".$gallery['sef_link']."&title=".$gallery['name']."&description=".$gallery['description']."&imageurl=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$gallery['photo']."' target='_blank' onclick='return Share.me(this);'><i class='fa fa-at' aria-hidden='true' title='Поделиться в Mail.ru'></i></a>
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
		echo "<a href='/".$prev['sef_link']."' style='color: #4c4c4c;' onmouseover='fontColor(\"prevIcon\", \"prevText\", 1)' onmouseout = 'fontColor(\"prevIcon\", \"prevText\", 0)'><i class='fa fa-angle-double-left' aria-hidden='true' id='prevIcon'></i> <span class='directionButton' id='prevText'>Назад</span></a>";

				if($nextResult->num_rows > 0) {
					echo "&nbsp;&nbsp; <span class='lightFont'>|</span> &nbsp;&nbsp;";
				}
			}

			if($nextResult->num_rows > 0) {
			echo "<a href='/".$next['sef_link']."' onmouseover='fontColor(\"nextIcon\", \"nextText\", 1)' onmouseout = 'fontColor(\"nextIcon\", \"nextText\", 0)'><span class='directionButton' id='nextText'>Вперед</span> <i class='fa fa-angle-double-right' aria-hidden='true' id='nextIcon'></i></a>";
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

			while($t = $tResult->fetch_array(MYSQLI_NUM)) {
				$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t[0]."'");
				$tag = $tagResult->fetch_assoc();

				if(urldecode(substr($_SERVER['REQUEST_URI'], 5)) != $tag['name']) {
					echo "<a href='/tag/".$tag['name']."'><span class='tagFont'>".$tag['name']."</span></a>";
				} else {
					echo "<span class='tagFont' style='color: #e0c1ac;'>".$tag['name']."</span>";
				}

				if($i < $tResult->num_rows) {
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
					<div class='postHeader'><a href='/".$post['sef_link']."'>".$post['name']."</a></div>
					<br /><br />
					<div class='blogDescription'><p>".$post['description']."</p></div>
					<img src='/img/photos/blog/main/".$post['photo']."' class='blogMainPhoto' />
					<div class='sectionHeader'><a href='/".$post['sef_link']."'><span class='openFont'>Смотреть далее <i class='fa fa-angle-double-right' aria-hidden='true'></i></span></a></div>
					<br /><br />
					<div class='separator'></div>
					<br />
					<div class='sectionHeader'>
						<div class='blogButtons'>
							<div class='shareButtonBlock'><span id='like".$post['id']."' class='like'"; if($liked[0] > 0) {echo "style='cursor: default; color: #b21c1c;'";} echo "><i class='fa fa-heart-o' aria-hidden='true' "; if($liked[0] == 0) {echo "onclick='likePost(\"".$post['id']."\")'";} echo "></i> <span id='likesCount".$post['id']."' class='likesCount'>".$likes."</span></span></div>
							<div class='shareButtonBlock'><a href='/".$post['sef_link']."#comments'><span class='blogButton'><i class='fa fa-comment-o' aria-hidden='true'></i> ".$comments."</span></a></div>
							<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'><i class='fa fa-share-square-o' aria-hidden='true'></i></div>
								<div class='shareButtonBlock' onmouseover='showShareBlock(\"".$post['id']."\", 1)' onmouseout='showShareBlock(\"".$post['id']."\", 0)'>
									<div class='shareBlock' id='shareBlock".$post['id']."'>
										<div class='shareLine'><div class='shareIcon'><a href='https://vk.com/share.php?url=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."&title=".$post['name']."&description=".$post['description']."&image=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$post['photo']."&noparse=true' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"vk\", 1)' onmouseout='shareButtonColor(\"vk\", 0)'><i class='fa fa-vk' aria-hidden='true' title='Поделиться в VK' id='vk-icon'></i></div><div class='shareName' id='vk-text'>VK</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://www.facebook.com/sharer/sharer.php?s=100&p%5Btitle%5D=".$post['name']."&p%5Bsummary%5D=".$post['description']."&p%5Burl%5D=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."&p%5Bimages%5D%5B0%5D=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$post['photo']."' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"facebook\", 1)' onmouseout='shareButtonColor(\"facebook\", 0)'><i class='fa fa-facebook' aria-hidden='true' title='Поделиться в Facebook' id='facebook-icon'></i></div><div class='shareName' id='facebook-text'>Facebook</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments=".$post['name']."&st._surl=".$_SERVER['HTTP_HOST']."/".$post['photo']."' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"odnoklassniki\", 1)' onmouseout='shareButtonColor(\"odnoklassniki\", 0)'><i class='fa fa-odnoklassniki' aria-hidden='true' title='Поделиться в Одноклассниках' id='odnoklassniki-icon'></i></div><div class='shareName' id='odnoklassniki-text'>Odnoklassniki</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Fkorsart.by%2F_display%2F&text=".$post['name']."&url=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."' target='_blank' onclick='return Share.me(this)' onmouseover='shareButtonColor(\"twitter\", 1)' onmouseout='shareButtonColor(\"twitter\", 0)'><i class='fa fa-twitter' aria-hidden='true' title='Поделиться в Twitter' id='twitter-icon'></i></div><div class='shareName' id='twitter-text'>Twitter</div></a></div>
										<div style='clear: both;'></div>
										<div class='shareLine'><div class='shareIcon'><a href='https://connect.mail.ru/share?url=".$_SERVER['HTTP_HOST']."/".$post['sef_link']."&title=".$post['name']."&description=".$post['description']."&imageurl=".$_SERVER['HTTP_HOST']."/img/photos/blog/main/".$post['photo']."' target='_blank' onclick='return Share.me(this);' onmouseover='shareButtonColor(\"mail-ru\", 1)' onmouseout='shareButtonColor(\"mail-ru\", 0)'><i class='fa fa-at' aria-hidden='true' title='Поделиться в Mail.ru' id='mail-ru-icon'></i></div><div class='shareName' id='mail-ru-text'>Mail.ru</div></a></div>	
									</div>
								</div>
							</div>
							<div style='clear: both;'></div>
						</div>
						<div class='blogTags'>
				";

				$i = 0;
				$tResult = $mysqli->query("SELECT * FROM posts_tags WHERE post_id = '".$post['id']."'");
				while($t = $tResult->fetch_assoc()) {
					$i++;

					$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t['tag_id']."'");
					$tag = $tagResult->fetch_assoc();

					if(urldecode(substr($_SERVER['REQUEST_URI'], 5)) == $tag['name']) {
						echo "<span class='postTagFont' style='color: #e0c1ac;'>".$tag['name']."</span>";
					} else {
						echo "<a href='/tag/".$tag['name']."'><span class='postTagFont'>".$tag['name']."</span></a>";
					}

					if($i < $tResult->num_rows) {
						echo ",&nbsp;&nbsp;";
					}
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