<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 23.08.2017
 * Time: 15:43
 */

include("../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$likesCountResult = $mysqli->query("SELECT COUNT(id) FROM likes WHERE post_id = '".$id."'");
$likesCount = $likesCountResult->fetch_array(MYSQLI_NUM);

echo $likesCount[0];