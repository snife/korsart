<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 25.08.2017
 * Time: 12:46
 */

include("../connect.php");
include("../helpers.php");

$id = $mysqli->real_escape_string($_POST['id']);

$commentResult = $mysqli->query("SELECT * FROM comments WHERE post_id = '".$id."' ORDER BY date ASC");
while($comment = $commentResult->fetch_assoc()) {
	echo "
		<div class='commentBlock'>
			<strong><span class='blogFont'>" .$comment['name']."</strong>, </span><span class='blogFont'>".dateForComment($comment['date'])."</span>
			<br /><br />
			<p class='blogFont'>".$comment['text']."</p>	
		</div>
	";
}