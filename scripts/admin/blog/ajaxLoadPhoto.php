<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.09.2017
 * Time: 11:48
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$photoResult = $mysqli->query("SELECT file FROM blog_photos WHERE id = '".$id."'");
$photo = $photoResult->fetch_array(MYSQLI_NUM);

echo $photo[0];