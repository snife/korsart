<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 14.08.2017
 * Time: 14:37
 */

include("../../connect.php");
include("../../image.php");

$req = false;
ob_start();

$name = $mysqli->real_escape_string($_POST['name']);
$link = $mysqli->real_escape_string($_POST['sefLink']);
$priority = $mysqli->real_escape_string($_POST['priority']);
$title = $mysqli->real_escape_string($_POST['title']);
$keywords = $mysqli->real_escape_string($_POST['keywords']);
$description = $mysqli->real_escape_string($_POST['description']);
$text = $mysqli->real_escape_string(nl2br($_POST['text']));
$categoryID = $mysqli->real_escape_string($_POST['category_id']);

$linkCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE sef_link = '".$link."'");
$linkCheck = $linkCheckResult->fetch_array(MYSQLI_NUM);

if($linkCheck[0] == 0) {
	$countResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE category_id = '".$categoryID."'");
	$count = $countResult->fetch_array(MYSQLI_NUM);

	if(!empty($_FILES['photo']['tmp_name'])) {
		if($_FILES['photo']['error'] == 0 and substr($_FILES['photo']['type'], 0, 5) == "image") {
			if(count($_FILES['photos']['name']) > 0) {
				$errors = 0;

				for($i = 0; $i < count($_FILES['photos']['name']); $i++) {
					if(empty($_FILES['photos']['tmp_name'][$i]) or $_FILES['photos']['error'][$i] != 0 or substr($_FILES['photos']['type'][$i], 0, 5) != "image") {
						$errors++;
					}
				}

				if($errors == 0) {
					if(($priority - $count[0]) != 1) {
						//Добавление не в конец, нужно увеличить приоритет последующих галерей на 1

						$i = 1;
						$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$categoryID."'");
						while($gallery = $galleryResult->fetch_assoc()) {
							if($i >= $priority) {
								$mysqli->query("UPDATE subcategories SET priority = '".($i + 1)."' WHERE id = '".$gallery['id']."'");
							}

							$i++;
						}
					}

					$mainPhotoName = randomName($_FILES['photo']['tmp_name']);
					$mainPhotoDBName = $mainPhotoName.".".substr($_FILES['photo']['name'], count($_FILES['photo']['name']) - 4, 4);
					$mainPhotoUploadDir = "../../../img/photos/gallery/main/";
					$mainPhotoTmpName = $_FILES['photo']['tmp_name'];
					$mainPhotoUpload = $mainPhotoUploadDir.$mainPhotoDBName;

					$text = "<div class=\"galleryTextContainer\">".$text."</div>";

					for($i = 0; $i < count($_FILES['photos']['tmp_name']); $i++) {
						$photoName = randomName($_FILES['photos']['tmp_name'][$i]);
						$photoDBName = $photoName.".".substr($_FILES['photos']['name'][$i], count($_FILES['photos']['name'][$i]) - 4, 4);
						$photoUploadDir = "../../../img/photos/gallery/content/";
						$photoTmpName = $_FILES['photos']['tmp_name'][$i];
						$photoUpload = $photoUploadDir.$photoDBName;

						move_uploaded_file($photoTmpName, $photoUpload);

						$text .= "<br /><br /><div class=\"galleryPhotoContainer\"><a href=\"/img/photos/gallery/content/".$photoDBName."\" class=\"lightview\" data-lightview-options=\"skin: \'light\'\" data-lightview-group=\"gallery\"><img src=\"/img/photos/gallery/content/".$photoDBName."\" class=\"galleryPhoto\" /></a></div>";
					}
					if($mysqli->query("INSERT INTO subcategories (category_id, name, sef_link, priority, title, keywords, description, photo, text) VALUES ('".$categoryID."', '".$name."', '".$link."', '".$priority."', '".$title."', '".$keywords."', '".$description."', '".$mainPhotoDBName."', '".$text."')")) {
						move_uploaded_file($mainPhotoTmpName, $mainPhotoUpload);
						echo "ok";
					} else {
						echo "failed";
					}
				} else {
					echo "photos format";
				}
			} else {
				echo "photos";
			}
		} else {
			echo "photo format";
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