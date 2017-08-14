<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 14.08.2017
 * Time: 10:21
 */

include("../../connect.php");

$reviewResult = $mysqli->query("SELECT * FROM reviews ORDER BY id DESC");
while($review = $reviewResult->fetch_assoc()) {
	echo "
		<div class='reviewContainer'>
			<div class='reviewPhoto'>
				<img src='/img/photos/reviews/".$review['photo']."' />
			</div>
			<div class='review'>
				<div class='reviewHeader'><h3>".$review['name']."</h3></div>
					<p>".$review['text']."</p>
					<p class='email'><a href='mailto: ".$review['email']."'>".$review['email']."</a></p>
				</div>
				<div class='reviewButtons'>
				<form method='post'>
					<label for='showButton'>Отображать? </label>
					<input type='checkbox' id='showButton".$review['id']."' name='show' onclick='showReview(\"".$review['id']."\", \"showButton".$review['id']."\")' "; if($review['showing'] == 1) {echo "checked";} echo ">
				</form>
				<br />
				<a href='index.php?id=".$review['id']."'><span><i class='fa fa-pencil-square' aria-hidden='true'></i> Редактировать</span></a>
				<br /><br />
				<span onclick='deleteReview(\"".$review['id']."\")'><i class='fa fa-trash' aria-hidden='true'></i> Удалить</span>
			</div>
		</div>
	";
}