<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 15:37
 */

include("../../connect.php");

$name = $mysqli->real_escape_string($_POST['name']);
$priority = $mysqli->real_escape_string($_POST['priority']);
$link = $mysqli->real_escape_string($_POST['link']);
$title = $mysqli->real_escape_string($_POST['title']);
$description = $mysqli->real_escape_string(nl2br($_POST['description']));

$maxPriorityResult = $mysqli->query("SELECT MAX(priority) FROM blog_subcategories");
$maxPriority = $maxPriorityResult->fetch_array(MYSQLI_NUM);

$galleryLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE sef_link = '".$link."'");
$galleryLinkCheck = $galleryLinkCheckResult->fetch_array(MYSQLI_NUM);

$blogLinkCheckResult = $mysqli->query("SELECT COUNT(id) FROM blog_subcategories WHERE sef_link = '".$link."'");
$blogLinkCheck = $blogLinkCheckResult->fetch_array(MYSQLI_NUM);

if($blogLinkCheck[0] == 0 and $galleryLinkCheck[0] == 0) {
	if($priority - $maxPriority[0] != 1) {
		$subcategoryResult = $mysqli->query("SELECT * FROM blog_subcategories");
		while($subcategory = $subcategoryResult->fetch_assoc()) {
			if($subcategory['priority'] >= $priority) {
				$mysqli->query("UPDATE blog_subcategories SET priority = '".($subcategory['priority'] + 1)."' WHERE id = '".$subcategory['id']."'");
			}
		}
	}

	if($mysqli->query("INSERT INTO blog_subcategories (name, sef_link, priority, title, keywords, description) VALUES ('".$name."', '".$link."', '".$priority."', '".$title."', '".$keywords."', '".$description."')")) {
		echo "ok";
	} else {
		echo "failed";
	}
} else {
	echo "link";
}