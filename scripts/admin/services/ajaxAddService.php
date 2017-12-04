<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 10.11.2017
 * Time: 11:51
 */

include("../../connect.php");
include("../../image.php");

$req = false;
ob_start();

$name = $mysqli->real_escape_string($_POST['serviceName']);
$description = $mysqli->real_escape_string(nl2br($_POST['serviceDescription']));
$list = $mysqli->real_escape_string($_POST['serviceList']);
$list = explode(',', $list);

$nameCheckResult = $mysqli->query("SELECT COUNT(id) FROM services WHERE name = '".$name."'");
$nameCheck = $nameCheckResult->fetch_array(MYSQLI_NUM);

if($nameCheck[0] == 0) {
	if(!empty($_FILES['servicePhoto']['tmp_name'])) {
		if($_FILES['servicePhoto']['error'] == 0 and substr($_FILES['servicePhoto']['type'], 0, 5) == "image") {
			$photoTmpName = $_FILES['servicePhoto']['tmp_name'];
			$photoName = randomName($photoTmpName);
			$photoDBName = $photoName.".".substr($_FILES['servicePhoto']['name'], count($_FILES['servicePhoto']['name']) - 4, 4);
			$photoUploadDir = "../../../img/services/";
			$photoUpload = $photoUploadDir.$photoDBName;

			$idResult = $mysqli->query("SELECT MAX(id) FROM services");
			$id = $idResult->fetch_array(MYSQLI_NUM);

			$newID = $id[0] + 1;

			if($mysqli->query("INSERT INTO services (id, name, photo, description) VALUES ('".$newID."', '".$name."', '".$photoDBName."', '".$description."')")) {
				resize($photoTmpName, 500);
				move_uploaded_file($photoTmpName, $photoUpload);

				foreach ($list as $point) {
					if(substr($point, 0, 1) == ' ') {
						$point = substr($point, 1);
					}

					if(!empty($point)) {
						$mysqli->query("INSERT INTO services_list (service_id, text) VALUES ('".$id."', '".$point."')");
					}
				}

				echo "ok";
			} else {
				echo "failed";
			}
		} else {
			echo "photo";
		}
	} else {
		echo "photo_empty";
	}
} else {
	echo "duplicate";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;