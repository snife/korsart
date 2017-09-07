<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.09.2017
 * Time: 12:06
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$textResult = $mysqli->query("SELECT description FROM blog_photos WHERE id = '".$id."'");
$text = $textResult->fetch_array(MYSQLI_NUM);

echo str_replace("<br />", "", $text[0]);