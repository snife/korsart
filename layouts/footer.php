<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 02.08.2017
 * Time: 17:01
 */

function showFooter() {
	$footer = "
		<footer>
			<a href='https://www.facebook.com/korsart' target='_blank'><i class='fa fa-facebook-square' aria-hidden='true'></i></a>
			&nbsp;&nbsp;&nbsp;
			<a href='https://www.instagram.com/korsart_wedding/' target='_blank'><i class='fa fa-instagram' aria-hidden='true'></i></a>
			&nbsp;&nbsp;&nbsp;
			<a href='https://vk.com/korsart' target='_blank'><i class='fa fa-vk' aria-hidden='true'></i></a>
			<br /><br />
			<span>Александр Корсаков &copy; 2012 - ".date('Y')."</span>
			<br />
			<span>+375 (29) 606-35-91</span>
			<br />
			<span>+375 (29) 505-35-91</span>
		</footer>
	";

	return $footer;
}