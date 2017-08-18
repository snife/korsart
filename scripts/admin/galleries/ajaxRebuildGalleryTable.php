<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 18.08.2017
 * Time: 12:28
 */

include("../../connect.php");

$id = $mysqli->real_escape_string($_POST['category_id']);

$subcategoryResult = $mysqli->query("SELECT * FROM subcategories WHERE category_id = '".$id."' ORDER BY priority");
while($subcategory = $subcategoryResult->fetch_assoc()) {
	echo "
		<div class='subcategory'>
			<a href='/".$subcategory['sef_link']."' target='_blank'><div class='subcategoryName'>".$subcategory['name']."</div></a>
			<div class='subcategoryButtons'>
				<a href='/admin/galleries/index.php?c=".$subcategory['category_id']."&id=".$subcategory['id']."'><span><i class='fa fa-pencil-square' aria-hidden='true'></i> Редактировать</span></a>
				<br />
				<span onclick='deleteGallery(\"".$subcategory['id']."\", \"".$subcategory['category_id']."\")'><i class='fa fa-trash' aria-hidden='true'></i> Удалить</span>
			</div>
		</div>
	";
}