<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 22.08.2017
 * Time: 12:40
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$postResult = $mysqli->query("SELECT * FROM posts WHERE id = '".$_POST['id']."'");
$post = $postResult->fetch_assoc();

if($mysqli->query("DELETE FROM posts WHERE id = '".$id."'")) {
	$photoResult = $mysqli->query("SELECT * FROM blog_photos WHERE post_id = '".$id."'");
	while($photo = $photoResult->fetch_assoc()) {
		if($mysqli->query("DELETE FROM blog_photos WHERE post_id = '".$id."'")) {
			unlink("../../../img/photos/blog/content/".$photo['file']);
		}
	}

	unlink("../../../img/photos/blog/main/".$post['photo']);

	$tResult = $mysqli->query("SELECT * FROM posts_tags WHERE post_id = '".$id."'");
	while($t = $tResult->fetch_assoc()) {
		if($mysqli->query("DELETE FROM posts_tags WHERE id = '".$t['id']."'")) {
			$tagResult = $mysqli->query("SELECT * FROM tags WHERE id = '".$t['tag_id']."'");
			$tag = $tagResult->fetch_assoc();

			if($tag['quantity'] == 1) {
				$mysqli->query("DELETE FROM tags WHERE id = '".$tag['id']."'");
			} else {
				$mysqli->query("UPDATE tags SET quantity = '".($tag['quantity'] - 1)."'");
			}
		}
	}

	echo "ok";
} else {
	echo "failed";
}