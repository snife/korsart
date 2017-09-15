<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 15:27
 */

include("../connect.php");

$name = $mysqli->real_escape_string($_POST['name']);
$email = $mysqli->real_escape_string($_POST['email']);
$phone = $mysqli->real_escape_string($_POST['phone']);
$date = $mysqli->real_escape_string($_POST['date']);
$t = $mysqli->real_escape_string($_POST['message']);

$from = $name." <".$email.">";
$subject = "Письмо с сайта korsart.by";
$reply = $email;
$to = ADMIN_EMAIL;

$hash = md5(rand(0, 1000000).date('Y-m-d H:i:s'));

$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

$text = "
	<div style='width: 100%; height: 100%; background-color: #fafafa; padding-top: 5px; padding-bottom: 20px;'>
		<center>
			<div style='padding: 20px; box-shadow: 0 5px 15px -4px rgba(0, 0, 0, 0.4); background-color: #fff; width: 600px; text-align: left;'>
				<b>Имя:</b> ".$name."
				<br />
				<b>Email:</b> ".$email."
				<br />
";

if(!empty($phone)) {
	$text .= "<b>Телефон:</b> ".$phone."<br />";
}

if(!empty($date)) {
	$text .= "<b>Дата события:</b> ".$date."<br />";
}

$text .= "
			<br />
			".$t."
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

if(mail($to, $subject, $message, $headers)) {
	echo "ok";
} else {
	echo "failed";
}