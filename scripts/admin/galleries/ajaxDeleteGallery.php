<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 11:30
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$galleryResult = $mysqli->query("SELECT * FROM subcategories WHERE id = '".$id."'");

if($galleryResult->num_rows > 0) {
	$gallery = $galleryResult->fetch_assoc();

	if($mysqli->query("DELETE FROM subcategories WHERE id = '".$id."'")) {
		unlink("../../../img/photos/gallery/main/".$gallery['photo']);

		$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$gallery['category_id']."'");
		while($subcategory = $subcategoryResult->fetch_assoc()) {
			if($subcategory['priority'] > $gallery['priority']) {
				$mysqli->query("UPDATE subcategories SET priority = '".($subcategory['priority'] - 1)."' WHERE id = '".$subcategory['id']."'");
			}
		}

		$photoResult = $mysqli->query("SELECT * FROM photos WHERE post_id = '".$id."'");

		if($photoResult->num_rows > 0) {
			while ($photo = $photoResult->fetch_assoc()) {
				if($mysqli->query("DELETE FROM photos WHERE id = '".$photo['id']."'")) {
					unlink("../../../img/photos/gallery/content/".$photo['file']);
				}
			}
		}

		echo "ok";
	} else {
		echo "failed";
	}
} else {
	echo "id";
}