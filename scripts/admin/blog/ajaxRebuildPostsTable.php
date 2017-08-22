<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 22.08.2017
 * Time: 12:48
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$postResult = $mysqli->query("SELECT * FROM posts WHERE subcategory_id = '" . $id . "' ORDER BY date DESC");
while ($post = $postResult->fetch_assoc()) {
	echo "
		<div class='subcategory'>
			<a href='/" . $post['sef_link'] . "' target='_blank'><div class='subcategoryName'>" . $post['name'] . "</div></a>
			<div class='subcategoryButtons'>
				<a href='post.php?c=".$_REQUEST['c']."&id=" . $post['id'] . "'><span><i class='fa fa-pencil-square' aria-hidden='true'></i> Редактировать</span></a>
				<br />
				<span onclick='deletePost(\"" . $post['id'] . "\", \"" . $post['subcategory_id'] . "\")'><i class='fa fa-trash' aria-hidden='true'></i> Удалить</span>
			</div>
		</div>
	";
}