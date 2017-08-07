<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 13:38
 */

include("../connect.php");
include("../image.php");

$req = false;
ob_start();

if(!empty($_FILES['reviewPhoto']['name'])) {
	if($_FILES['reviewPhoto']['error'] == 0 and substr($_FILES['reviewPhoto']['type'], 0, 5) == "image") {
		$name = $mysqli->real_escape_string($_POST['reviewName']);
		$email = $mysqli->real_escape_string($_POST['reviewEmail']);
		$text = $mysqli->real_escape_string(nl2br($_POST['reviewText']));

		$photoName = randomName($_FILES['reviewPhoto']['tmp_name']);
		$photoDBName = $photoName.".".substr($_FILES['reviewPhoto']['name'], count($_FILES['reviewPhoto']['name']) - 4, 4);
		$photoUploadDir = '../../img/photos/reviews/';
		$photoTmpName = $_FILES['reviewPhoto']['tmp_name'];
		$photoUpload = $photoUploadDir.$photoDBName;

		if($mysqli->query("INSERT INTO reviews (name, email, text, photo, showing) VALUES ('".$name."', '".$email."', '".$text."', '".$photoDBName."', '0')")) {
			resize($photoTmpName, 300);
			move_uploaded_file($photoTmpName, $photoUpload);

			echo "ok";
		} else {
			echo "failed";
		}
	} else {
		echo "file type";
	}
} else {
	echo "no photo";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;