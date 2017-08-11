<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 11.08.2017
 * Time: 20:18
 */

include("../../connect.php");

$tableName = $mysqli->real_escape_string($_POST['tableName']);

$sliderResult = $mysqli->query("SELECT * FROM ".$tableName." ORDER BY id");
while($slider = $sliderResult->fetch_assoc()) {
	echo "
		<div class='sliderPhotoPreview'>
			<a href='/img/photos/".$tableName."/".$slider['photo']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='slider'><img src='/img/photos/".$tableName."/".$slider['photo']."' /></a>
			<br />
			<span onclick='deletePhoto(\"".$slider['id']."\", \"".$tableName."\")'>Удалить</span>
		</div>
	";
}