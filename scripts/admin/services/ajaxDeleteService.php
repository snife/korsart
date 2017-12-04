<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 09.11.2017
 * Time: 14:35
 */

include("../../connect.php");

$req = false;
ob_start();

$id = $mysqli->real_escape_string($_POST['service']);

$serviceResult = $mysqli->query("SELECT * FROM services WHERE id = '".$id."'");
$service = $serviceResult->fetch_assoc();

if($mysqli->query("DELETE FROM services WHERE id = '".$id."'")) {
	$mysqli->query("DELETE FROM services_list WHERE service_id = '".$id."'");
	unlink("../../../img/services/".$service['photo']);
	echo "ok";
} else {
	echo "failed";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;