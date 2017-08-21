<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 21.08.2017
 * Time: 8:21
 */

include("../../connect.php");

$name = $mysqli->real_escape_string($_POST['name']);
$priority = $mysqli->real_escape_string($_POST['priority']);
$link = $mysqli->real_escape_string($_POST['link']);
$title = $mysqli->real_escape_string($_POST['title']);
$description = $mysqli->real_escape_string(nl2br($_POST['description']));
$id = $mysqli->real_escape_string($_POST['id']);

$galleryLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE sef_link = '".$link."'");
$galleryLinkCheck = $galleryLinkCheckResult->fetch_array(MYSQLI_NUM);

$blogLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE sef_link = '".$link."' AND id <> '".$id."'");
$blogLinkCheck = $blogLinkCheckResult->fetch_array(MYSQLI_NUM);

if($blogLinkCheck[0] == 0 and $galleryLinkCheck[0] == 0) {
	$subcategoryResult = $mysqli->query("SELECT * FROM blog_subcategories WHERE id = '".$id."'");
	$subcategory = $subcategoryResult->fetch_assoc();

	if($priority != $subcategory['priority']) {
		$sResult = $mysqli->query("SELECT * FROM blog_subcategories");

		if($priority < $subcategory['priority']) {
			while($s = $sResult->fetch_assoc()) {
				if($s['priority'] >= $priority and $s['priority'] < $subcategory['priority']) {
					$mysqli->query("UPDATE blog_subcategories SET priority = '".($s['priority'] + 1)."' WHERE id = '".$s['id']."'");
				}
			}
		} else {
			while ($s = $sResult->fetch_assoc()) {
				if($s['priority'] > $subcategory['priority'] and $s['priority'] <= $priority) {
					$mysqli->query("UPDATE blog_subcategories SET priority = '".($s['priority'] - 1)."' WHERE id = '".$s['id']."'");
				}
			}
		}
	}

	if($mysqli->query("UPDATE blog_subcategories SET name = '".$name."', sef_link = '".$link."', priority = '".$priority."', title = '".$title."', keywords = '".$keywords."', description = '".$description."' WHERE id = '".$id."'")) {
		echo "ok";
	} else {
		echo "failed";
	}
} else {
	echo "link";
}