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
$text = $mysqli->real_escape_string($_POST['message']);

$from = $name." <".$email.">";
$subject = "Письмо с сайта korsart.by";
$reply = $email;
$to = ADMIN_EMAIL;

$hash = md5(date('r', time()));

$headers = "From: ".$from."\nReply-To: ".$reply."\nMIME-Version: 1.0";
$headers .= "\nContent-Type: multipart/mixed; boundary = \"PHP-mixed-".$hash."\"\n\n";

$message = "--PHP-mixed-".$hash."\n";
$message .= "Content-Type: text/html; charset=\"utf-8\"\n";
$message .= "Content-Transfer-Encoding: 8bit\n\n";
$message .= "Имя: ".$name."\n";
$message .= "Email: ".$email."\n";

if(!empty($phone)) {
	$message .= "Телефон: ".$phone."\n";
}

if(!empty($date)) {
	$message .= "Дата события: ".$date."\n\n";
}

$message .= $text."\n";
$message .= "--PHP-mixed-".$hash."\n";

if(mail($to, $subject, $message, $headers)) {
	echo "ok";
} else {
	echo "failed";
}