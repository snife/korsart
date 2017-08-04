<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 03.08.2017
 * Time: 13:18
 */

include("../connect.php");

$id = $mysqli->real_escape_string($_POST['id']);

$idCheckResult = $mysqli->query("SELECT COUNT(id) FROM categories WHERE id = '".$id."'");
$idCheck = $idCheckResult->fetch_array(MYSQLI_NUM);

if($idCheck[0] > 0) {
	$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$id."' ORDER BY priority");
	while($subcategory = $subcategoryResult->fetch_assoc()) {
		echo "<div class='mobileSubmenuPoint'><a href='/".$subcategory['sef_link']."'><span class='submenuFont'>".$subcategory['name']."</span></a></div>";
	}

	echo "<div style='margin-top: 20px;'></div>";
}