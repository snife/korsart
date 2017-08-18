<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 10:35
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$photoResult = $mysqli->query("SELECT * FROM photos WHERE post_id = '".$id."'");
while($photo = $photoResult->fetch_assoc()) {
	echo "
		<div class='galleryPhoto'>
			<a href='/img/photos/gallery/content/".$photo['file']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='photos'><img src='/img/photos/gallery/content/".$photo['file']."' /></a>
			<br />
			<span onclick='deleteGalleryPhoto(\"".$photo['id']."\", \"".$id."\")' class='adminLink'>Удалить</span>
		</div>
	";
}