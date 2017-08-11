<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 20:44
 */

include("../../connect.php");
include("../../image.php");

$req = false;
ob_start();

$tableName = $mysqli->real_escape_string($_POST['tableName']);

$errors = 0;
if(count($_FILES['sliderPhotos']['name']) > 0) {
	for($i = 0; $i < count($_FILES['sliderPhotos']['name']); $i++) {
		if(empty($_FILES['sliderPhotos']['tmp_name'][$i] or $_FILES['sliderPhotos']['error'][$i] == 1 or substr($_FILES['sliderPhotos']['type'][$i], 0, 5) != "image")) {
			$errors++;
		}
	}

	if($errors == 0) {
		$success = 0;
		for($i = 0; $i < count($_FILES['sliderPhotos']['name']); $i++) {
			$photoName = randomName($_FILES['sliderPhotos']['tmp_name'][$i]);
			$photoDBName = $photoName.".".substr($_FILES['sliderPhotos']['name'][$i], count($_FILES['sliderPhotos']['name'][$i]) - 4, 4);
			$photoUploadDir = "../../../img/photos/".$tableName."/";
			$photoTmpName = $_FILES['sliderPhotos']['tmp_name'][$i];
			$photoUpload = $photoUploadDir.$photoDBName;

			if($mysqli->query("INSERT INTO ".$tableName." (photo) VALUES ('".$photoDBName."')")) {
				move_uploaded_file($photoTmpName, $photoUpload);
				$success++;
			}
		}

		if($success == 0) {
			echo "failed";
		} elseif($success == count($_FILES['sliderPhotos']['name'])) {
			echo "ok";
		} else {
			echo "partly";
		}
	} else {
		echo "files";
	}
} else {
	echo "empty";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;