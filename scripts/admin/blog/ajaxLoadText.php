<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 22.08.2017
 * Time: 9:37
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$textResult = $mysqli->query("SELECT text FROM posts WHERE id = '".$id."'");
$text = $textResult->fetch_array(MYSQLI_NUM);

echo $text[0];