<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 17.08.2017
 * Time: 14:17
 */

include("../../connect.php");
include("../../image.php");

$req = false;
ob_start();

$name = $mysqli->real_escape_string($_POST['name']);
$link = $mysqli->real_escape_string($_POST['link']);
$priority = $mysqli->real_escape_string($_POST['priority']);
$title = $mysqli->real_escape_string($_POST['title']);
$keywords = $mysqli->real_escape_string($_POST['keywords']);
$description = $mysqli->real_escape_string(nl2br($_POST['description']));
$text = $mysqli->real_escape_string(nl2br($_POST['text']));
$id = $mysqli->real_escape_string($_POST['subcategory_id']);

$linkCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE sef_link = '".$link."' AND id <> '".$id."'");
$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

$blogLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE sef_link = '".$link."'");
$blogLinkCheck = $blogLinkCheckResult->fetch_array(MYSQLI_NUM);

$postLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts WHERE sef_link = '".$link."'");
$postLinkCheck = $postLinkCheckResult->fetch_array(MYSQLI_NUM);

$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE id = '".$id."'");
$gallery = $galleryResult->fetch_assoc();

if($linkCheck[0] == 0 and $blogLinkCheck[0] == 0 and $postLinkCheck[0] == 0) {
	//Проверка приоритета
	if($gallery['priority'] != $priority) {
		$gResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$gallery['category_id']."'");

		if($priority < $gallery['priority']) {
			//Если приоритет уменьшается:
			//	от $priority до $gallery['priority'] увеличить приоритет на 1
			//	изменить приоритет у галереи

			while($g = $gResult->fetch_assoc()) {
				if($g['priority'] >= $priority and $g['priority'] < $gallery['priority']) {
					$mysqli->query("UPDATE subcategories SET priority = '".($g['priority'] + 1)."' WHERE id = '".$g['id']."'");
				}
			}
		} else {
			//Если приоритет увеличивается
			//	от $gallery['priority'] до $priority уменьшить приоритет на 1
			//	изменить приоритет у галереи

			while($g = $gResult->fetch_assoc()) {
				if($g['priority'] >= $gallery['priority'] and $g['priority'] <= $priority) {
					$mysqli->query("UPDATE subcategories SET priority = '".($g['priority'] - 1)."' WHERE id = '".$g['id']."'");
				}
			}
		}

		$mysqli->query("UPDATE subcategories SET priority = '".$priority."' WHERE id = '".$id."'");
	}

	if(!empty($_FILES['photo']['name'])) {
		if($_FILES['photo']['error'] == 0 and substr($_FILES['photo']['type'], 0, 5) == "image") {
			$mainPhotoName = randomName($_FILES['photo']['tmp_name']);
			$mainPhotoDBName = $mainPhotoName.".".substr($_FILES['photo']['name'], count($_FILES['photo']['name']) - 4, 4);
			$mainPhotoUploadDir = "../../../img/photos/gallery/main/";
			$mainPhotoTmpName = $_FILES['photo']['tmp_name'];
			$mainPhotoUpload = $mainPhotoUploadDir.$mainPhotoDBName;

			if($mysqli->query("UPDATE subcategories SET photo = '".$mainPhotoDBName."' WHERE id = '".$id."'")) {
				unlink($mainPhotoUploadDir.$gallery['photo']);
				move_uploaded_file($mainPhotoTmpName, $mainPhotoUpload);
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

		for($i = 0; $i < count($_FILES['photos']['name']); $i++) {
			if(empty($_FILES['photos']['tmp_name'][$i]) or $_FILES['photos']['error'][$i] != 0 or substr($_FILES['photos']['type'][$i], 0, 5) != "image") {
				$errors++;
			}
		}

		if($errors == 0) {
			for($i = 0; $i < count($_FILES['photos']['name']); $i++) {
				$photoName = randomName($_FILES['photos']['tmp_name'][$i]);
				$photoDBName = $photoName.".".substr($_FILES['photos']['name'][$i], count($_FILES['photos']['name'][$i]) - 4, 4);
				$photoUploadDir = "../../../img/photos/gallery/content/";
				$photoTmpName = $_FILES['photos']['tmp_name'][$i];
				$photoUpload = $photoUploadDir.$photoDBName;

				if($mysqli->query("INSERT INTO photos (file, post_id) VALUES('".$photoDBName."', '".$id."')")) {
					move_uploaded_file($photoTmpName, $photoUpload);

					$text .= "<br /><br /><div class=\"galleryPhotoContainer\"><a href=\"/img/photos/gallery/content/".$photoDBName."\" class=\"lightview\" data-lightview-options=\"skin: \'light\'\" data-lightview-group=\"gallery\"><img src=\"/img/photos/gallery/content/".$photoDBName."\" class=\"galleryPhoto\" /></a></div>";
				}
			}
		} else {
			echo "photos type";

			$req = ob_get_contents();
			ob_end_clean();
			echo json_encode($req);

			exit;
		}
	}

	if(substr($text, 0, 4) != "<div") {
		$text = "<div class=\"galleryTextContainer\">".$text."</div>";
	}

	if($mysqli->query("UPDATE subcategories SET name = '".$name."', sef_link = '".$link."', title = '".$title."', keywords = '".$keywords."', description = '".$description."', text = '".$text."' WHERE id = '".$id."'")) {
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