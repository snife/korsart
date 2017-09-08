<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 22.08.2017
 * Time: 10:28
 */

include ("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$photoResult = $mysqli->query("SELECT * FROM blog_photos WHERE post_id = '".$id."'");
while($photo = $photoResult->fetch_assoc()) {
	echo "
		<div class='galleryPhoto'>
			<a href='/img/photos/blog/content/".$photo['file']."' class='lightview' data-lightview-options='skin: \"light\"' data-lightview-group='photos'><img src='/img/photos/blog/content/".$photo['file']."' /></a>
			<br />
			<span onclick='deleteBlogPhoto(\"".$photo['id']."\", \"".$_REQUEST['id']."\")' class='adminLink'>Удалить</span>
			<br />
			<a data-remodal-target='modal'><span class='adminLink' onclick='setModalID(\"".$photo['id']."\")'>Описание</span></a>
		</div>
	";
}