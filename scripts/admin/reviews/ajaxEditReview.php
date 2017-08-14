<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 14.08.2017
 * Time: 9:21
 */

include("../../connect.php");
include("../../image.php");

$req = false;
ob_start();

$id = $mysqli->real_escape_string($_POST['id']);
$name = $mysqli->real_escape_string($_POST['name']);
$text = $mysqli->real_escape_string(nl2br($_POST['text']));

if($mysqli->query("UPDATE reviews SET name = '".$name."', text = '".$text."' WHERE id = '".$id."'")) {
	if(!empty($_FILES['photo']['name'])) {
		if($_FILES['photo']['error'] == 0 and substr($_FILES['photo']['type'], 0, 5) == "image") {
			$photoName = randomName($_FILES['photo']['tmp_name']);
			$photoDBName = $photoName.".".substr($_FILES['photo']['name'], count($_FILES['photo']['name']) - 4, 4);
			$photoUploadDir = '../../../img/photos/reviews/';
			$photoTmpName = $_FILES['photo']['tmp_name'];
			$photoUpload = $photoUploadDir.$photoDBName;

			$reviewResult = $mysqli->query("SELECT * FROM reviews WHERE id = '".$id."'");
			$review = $reviewResult->fetch_assoc();

			if($mysqli->query("UPDATE reviews SET photo = '".$photoDBName."' WHERE id = '".$id."'")) {
				resize($photoTmpName, 300);
				move_uploaded_file($photoTmpName, $photoUpload);
				unlink($photoUploadDir.$review['photo']);

				echo "ok";
			} else {
				echo "photo failed";
			}
		} else {
			echo "photo";
		}
	} else {
		echo "ok";
	}
} else {
	echo "failed";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;