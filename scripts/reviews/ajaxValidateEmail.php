<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 10:54
 */

if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	echo "valid";
}