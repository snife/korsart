<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 25.08.2017
 * Time: 9:59
 */

include("../connect.php");
include("../recaptcha.php");

$req = false;
ob_start();

$id = $mysqli->real_escape_string($_POST['id']);
$name = $mysqli->real_escape_string($_POST['name']);
$text = $mysqli->real_escape_string($_POST['text']);

$secret = "6LefJi4UAAAAAGn6L5WwkKYowHdDzPkENB6SDuQo";
$response = null;
$reCaptcha = new ReCaptcha($secret);

if($_POST['g-recaptcha-response']) {
	$response = $reCaptcha->verifyResponse(
		$_SERVER["REMOTE_ADDR"],
		$_POST['g-recaptcha-response']
	);
}

if($response != null && $response->success) {
	if($mysqli->query("INSERT INTO comments (post_id, name, text, ip, date) VALUES ('".$id."', '".$name."', '".$text."', '".$_SERVER['REMOTE_ADDR']."', '".date('Y-m-d H:i:s')."')")) {
		echo "ok";
	} else {
		echo "failed";
	}
} else {
	echo "captcha";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;