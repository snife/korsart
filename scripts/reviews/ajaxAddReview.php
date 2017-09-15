<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 11:10
 */

include("../connect.php");
include("../image.php");

$req = false;
ob_start();

if(!empty($_FILES['photo']['name'])) {
	if($_FILES['photo']['error'] == 0 and substr($_FILES['photo']['type'], 0, 5) == "image") {
		$name = $mysqli->real_escape_string($_POST['name']);
		$email = $mysqli->real_escape_string($_POST['email']);
		$text = $mysqli->real_escape_string(nl2br($_POST['text']));

		$photoName = randomName($_FILES['photo']['tmp_name']);
		$photoDBName = $photoName.".".substr($_FILES['photo']['name'], count($_FILES['photo']['name']) - 4, 4);
		$photoUploadDir = '../../img/photos/reviews/';
		$photoTmpName = $_FILES['photo']['tmp_name'];
		$photoUpload = $photoUploadDir.$photoDBName;

		if($mysqli->query("INSERT INTO reviews (name, email, text, photo, showing) VALUES ('".$name."', '".$email."', '".$text."', '".$photoDBName."', '0')")) {
			resize($photoTmpName, 300);
			move_uploaded_file($photoTmpName, $photoUpload);

			$from = "korsart.by <no-reply@korsart.by>";
			$to = ADMIN_EMAIL;
			$subject = "На сайте korsart.by был добавлен отзыв";

			$hash = md5(rand(0, 1000000).date('Y-m-d H:i:s'));

			$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
			$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

			$text = "
				<div style='width: 100%; height: 100%; background-color: #fafafa; padding-top: 5px; padding-bottom: 20px;'>
					<center>
						<div style='padding: 20px; box-shadow: 0 5px 15px -4px rgba(0, 0, 0, 0.4); background-color: #fff; width: 600px; text-align: left;'>
							<p>На сайте korsart.by был добавлен новый отзыв. Он будет отображён только после валидации в разделе 'Отзывы' панели адмнистрирования.</p>
						</div>
						<br /><br />
					</center>
				</div>
			";

			$message = "--PHP-mixed-".$hash."\n";
			$message .= "Content-Type: text/html; charset=\"utf-8\"\n";
			$message .= "Content-Transfer-Encoding: 8bit\n\n";
			$message .= $text."\n";
			$message .= "--PHP-mixed-".$hash."\n";

			mail($to, $subject, $message, $headers);

			echo "ok";
		} else {
			echo "failed";
		}
	} else {
		echo "file type";
	}
} else {
	echo "no photo";
}

$req = ob_get_contents();
ob_end_clean();
echo json_encode($req);

exit;