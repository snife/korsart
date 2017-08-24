<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 23.08.2017
 * Time: 15:35
 */

include('../connect.php');

$id = $mysqli->real_escape_string($_POST['id']);

$likeCheckResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$id."' AND ip = '".$_SERVER['REMOTE_ADDR']."'");
$likeCheck = $likeCheckResult->fetch_array(MYSQLI_NUM);

if($likeCheck[0] == 0) {
	if($mysqli->query("INSERT INTO likes (post_id, ip, date) VALUES ('".$id."', '".$_SERVER['REMOTE_ADDR']."', '".date('Y-m-d H:i:s')."')")) {
		echo "ok";
	} else {
		echo "failed";
	}
}