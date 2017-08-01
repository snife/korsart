<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 31.07.2017
 * Time: 16:14
 */

include("../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$subcategoriesCountResult = $mysqli->query("SELECT COUNT(id) FROM subcategories WHERE category_id = '".$id."'");
$subcategoriesCount = $subcategoriesCountResult->fetch_array(MYSQLI_NUM);

if($subcategoriesCount[0] > 0) {
	echo "<div class='subMenuPoint'><div class='subMenuLine'></div></div>";

	$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$id."' ORDER BY priority");
	while($subcategory = $subcategoryResult->fetch_assoc()) {
		echo "
			<div class='subMenuPoint'>
				<div class='subMenuLine'>
					<a href='/".$subcategory['sef_link']."'><span class='menuFont'>".mb_strtolower($subcategory['name'])."</span></a>
				</div>
			</div>
		";
	}
}