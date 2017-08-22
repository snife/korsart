<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 22.08.2017
 * Time: 11:48
 */

include ('../../connect.php');
include ('../../image.php');

$req = false;
ob_start();

$name = $mysqli->real_escape_string($_POST['name']);
$link = $mysqli->real_escape_string($_POST['link']);
$description = $mysqli->real_escape_string(nl2br($_POST['description']));
$text = $mysqli->real_escape_string(nl2br($_POST['text']));
$tags = $mysqli->real_escape_string($_POST['tags']);
$id = $mysqli->real_escape_string($_POST['id']);

$galleryLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE sef_link = '".$link."'");
$galleryLinkCheck = $galleryLinkCheckResult->fetch_array(MYSQLI_NUM);

$blogLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE sef_link = '".$link."'");
$blogLinkCheck = $blogLinkCheckResult->fetch_array(MYSQLI_NUM);

$postLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts WHERE sef_link = '".$link."' AND id <> '".$id."'");
$postLinkCheck = $postLinkCheckResult->fetch_array(MYSQLI_NUM);

if($galleryLinkCheck[0] == 0 and $blogLinkCheck[0] == 0 and $postLinkCheck[0] == 0) {
	$postResult = $mysqli->query("SELECT * FROM posts WHERE id = '".$id."'");
	$post = $postResult->fetch_assoc();

	if(!empty($_FILES['photo']['tmp_name'])) {
		if($_FILES['photo']['error'] == 0 and substr($_FILES['photo']['type'], 0, 5) == "image") {
			$mainPhotoName = randomName($_FILES['photo']['tmp_name']);
			$mainPhotoDBName = $mainPhotoName.".".substr($_FILES['photo']['name'], count($_FILES['photo']['name']) - 4, 4);
			$mainPhotoUploadDir = "../../../img/photos/blog/main/";
			$mainPhotoTmpName = $_FILES['photo']['tmp_name'];
			$mainPhotoUpload = $mainPhotoUploadDir.$mainPhotoDBName;

			if($mysqli->query("UPDATE posts SET photo = '".$mainPhotoDBName."'")) {
				unlink("../../../img/photos/blog/main/".$post['photo']);
				move_uploaded_file($mainPhotoTmpName, $mainPhotoUpload);
			} else {
				echo "photo failed";

				$req = ob_get_contents();
				ob_end_clean();
				echo json_encode($req);

				exit;
			}
		} else {
			echo "photo type";

			$req = ob_get_contents();
			ob_end_clean();
			echo json_encode($req);

			exit;
		}
	}

	$filesCount = 0;

	foreach ($_FILES['photos']['tmp_name'] as $file) {
		if(is_uploaded_file($file)) {
			$filesCount++;
		}
	}

	if($filesCount > 0) {
		$errors = 0;

		for($i = 0; $i < $filesCount; $i++) {
			if(empty($_FILES['photos']['tmp_name'][$i]) or $_FILES['photos']['error'][$i] != 0 or substr($_FILES['photos']['type'][$i], 0, 5) != "image") {
				$errors++;
			}
		}

		if($errors == 0) {
			for($i = 0; $i < $filesCount; $i++) {
				$photoName = randomName($_FILES['photos']['tmp_name'][$i]);
				$photoDBName = $photoName.".".substr($_FILES['photos']['name'][$i], count($_FILES['photos']['name'][$i]) - 4, 4);
				$photoUploadDir = "../../../img/photos/blog/content/";
				$photoTmpName = $_FILES['photos']['tmp_name'][$i];
				$photoUpload = $photoUploadDir.$photoDBName;

				if($mysqli->query("INSERT INTO blog_photos (file, post_id) VALUES ('".$photoDBName."', '".$id."')")) {
					move_uploaded_file($photoTmpName, $photoUpload);
				}
			}
		} else {
			echo "photos type";
		}
	}

	$tagsList = array();
	$tags = explode(",", $tags);

	foreach ($tags as $tag) {
		if($tag[0] == ' ') {
			$tag = substr($tag, 1);
		}

		array_push($tagsList, $tag);
	}

	array_unique($tagsList);

	for($i = 0; $i < count($tagsList); $i++) {
		$tagResult = $mysqli->query("SELECT * FROM tags WHERE name = '".$tagsList[$i]."'");

		if($tagResult->num_rows == 0) {
			$mysqli->query("INSERT INTO tags (name, quantity) VALUES ('".$tagsList[$i]."', '1')");

			$tagIDResult = $mysqli->query("SELECT id FROM tags WHERE name = '".$tagsList[$i]."'");
			$tagID = $tagIDResult->fetch_array(MYSQLI_NUM);

			$mysqli->query("INSERT INTO posts_tags (post_id, tag_id) VALUES ('".$id."', '".$tagID[0]."')");
		} else {
			$tag = $tagResult->fetch_assoc();

			$tagCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts_tags WHERE post_id = '".$id."' AND tag_id = '".$tag['id']."'");
			$tagCheck = $tagCheckResult->fetch_array(MYSQLI_NUM);

			if($tagCheck[0] == 0) {
				$mysqli->query("UPDATE tags SET quantity = '".($tag['quantity'] + 1)."' WHERE id = '".$tag['id']."'");
				$mysqli->query("INSERT INTO posts_tags (post_id, tag_id) VALUES ('".$id."', '".$tag['id']."')");
			}
		}
	}

	if($mysqli->query("UPDATE posts SET name='".$name."', sef_link = '".$link."', description = '".$description."', text = '".$text."' WHERE id = '".$id."'")) {
		echo "ok";
	} else {
		echo "failed";
	}
} else {
	echo "link";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;