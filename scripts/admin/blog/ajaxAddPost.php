<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 21.08.2017
 * Time: 11:15
 */

include ("../../connect.php");
include ("../../image.php");

$req = false;
ob_start();

$name = $mysqli->real_escape_string($_POST['name']);
$link = $mysqli->real_escape_string($_POST['link']);
$description = $mysqli->real_escape_string(nl2br($_POST['description']));
$text = $mysqli->real_escape_string(nl2br($_POST['text']));
$tags = $mysqli->real_escape_string($_POST['tags']);
$id = $mysqli->real_escape_string($_POST['subcategory_id']);

$galleryLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE sef_link = '".$link."'");
$galleryLinkCheck = $galleryLinkCheckResult->fetch_array(MYSQLI_NUM);

$blogLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE sef_link = '".$link."'");
$blogLinkCheck = $blogLinkCheckResult->fetch_array(MYSQLI_NUM);

$postLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM posts WHERE sef_link = '".$link."'");
$postLinkCheck = $postLinkCheckResult->fetch_array(MYSQLI_NUM);

if($galleryLinkCheck[0] == 0 and $blogLinkCheck[0] == 0 and $postLinkCheck[0] == 0) {
	if(!empty($_FILES['photo']['tmp_name'])) {
		if($_FILES['photo']['error'] == 0 and substr($_FILES['photo']['type'], 0, 5) == "image") {
			$mainPhotoName = randomName($_FILES['photo']['tmp_name']);
			$mainPhotoDBName = $mainPhotoName.".".substr($_FILES['photo']['name'], count($_FILES['photo']['name']) - 4, 4);
			$mainPhotoUploadDir = "../../../img/photos/blog/main/";
			$mainPhotoTmpName = $_FILES['photo']['tmp_name'];
			$mainPhotoUpload = $mainPhotoUploadDir.$mainPhotoDBName;

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
					$tagsList = array();
					$tags = explode(",", $tags);

					foreach ($tags as $tag) {
						if($tag[0] == ' ') {
							$tag = substr($tag, 1);
						}

						array_push($tagsList, $tag);
					}

					array_unique($tagsList);

					if($mysqli->query("INSERT INTO posts (subcategory_id, name, sef_link, description, photo, text, date) VALUES ('".$id."', '".$name."', '".$link."', '".$description."', '".$mainPhotoDBName."', '".$text."', '".date('Y-m-d H:i:s')."')")) {
						move_uploaded_file($mainPhotoTmpName, $mainPhotoUpload);

						$newIDResult = $mysqli->query("SELECT id FROM posts ORDER BY id DESC LIMIT 1");
						$newID = $newIDResult->fetch_array(MYSQLI_NUM);
						$newID = $newID[0];

						for($i = 0; $i < count($tagsList); $i++) {
							$tagResult = $mysqli->query("SELECT * FROM tags WHERE name = '".$tagsList[$i]."'");

							if($tagResult->num_rows == 0) {
								$mysqli->query("INSERT INTO tags (name, quantity) VALUES ('".$tagsList[$i]."', '1')");

								$tagIDResult = $mysqli->query("SELECT id FROM tags WHERE name = '".$tagsList[$i]."'");
								$tagID = $tagIDResult->fetch_array(MYSQLI_NUM);

								$mysqli->query("INSERT INTO posts_tags (post_id, tag_id, subcategory_id) VALUES ('".$newID."', '".$tagID[0]."', '".$id."')");
							} else {
								$tag = $tagResult->fetch_assoc();
								$mysqli->query("UPDATE tags SET quantity = '".($tag['quantity'] + 1)."' WHERE id = '".$tag['id']."'");
								$mysqli->query("INSERT INTO posts_tags (post_id, tag_id, subcategory_id) VALUES ('".$newID."', '".$tag['id']."', '".$id."')");
							}
						}

						for($i = 0; $i < $filesCount; $i++) {
							$photoName = randomName($_FILES['photos']['tmp_name'][$i]);
							$photoDBName = $photoName.".".substr($_FILES['photos']['name'][$i], count($_FILES['photos']['name'][$i]) - 4, 4);
							$photoUploadDir = "../../../img/photos/blog/content/";
							$photoTmpName = $_FILES['photos']['tmp_name'][$i];
							$photoUpload = $photoUploadDir.$photoDBName;

							if($mysqli->query("INSERT INTO blog_photos (file, post_id) VALUES ('".$photoDBName."', '".$newID."')")) {
								move_uploaded_file($photoTmpName, $photoUpload);
							}
						}

						echo "ok";
					} else {
						echo "failed";
					}
				} else {
					echo "photos type";
				}
			} else {
				echo "photos";
			}
		} else {
			echo "photo type";
		}
	} else {
		echo "photo";
	}
} else {
	echo "link";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;