<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 16.08.2017
 * Time: 10:36
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$textResult = $mysqli->query("SELECT text FROM subcategories WHERE id = '".$id."'");
$text = $textResult->fetch_array(MYSQLI_NUM);

$text = explode("</div>", $text[0]);
$text = $text[0]."</div>";

echo $text;