<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 20:09
 */

include ("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);
$tableName = $mysqli->real_escape_string($_POST['tableName']);

$idCheckResult = $mysqli->query("SELECT COUNT(id) FROM ".$tableName." WHERE id = '".$id."'");
$idCheck = $idCheckResult->fetch_array(MYSQLI_NUM);

if($idCheck[0] == 1) {
	$photoResult = $mysqli->query("SELECT photo FROM ".$tableName." WHERE id = '".$id."'");
	$photo = $photoResult->fetch_array(MYSQLI_NUM);

	if($mysqli->query("DELETE FROM ".$tableName." WHERE id = '".$id."'")) {
		unlink("../../../img/photos/".$tableName."/".$photo[0]);

		echo "ok";
	} else {
		echo "failed";
	}
}