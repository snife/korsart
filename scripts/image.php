<?php
/**
 * Created by PhpStorm.
 * User: jeyfost
 * Date: 07.08.2017
 * Time: 11:50
 */

function image_resize($source_path, $destination_path, $new_width, $quality = FALSE, $new_height = FALSE)
{
	ini_set("gd.jpeg_ignore_warning", 1);

	list($old_width, $old_height, $type) = getimagesize($source_path);

	switch($type)
	{
		case IMAGETYPE_JPEG:
			$typestr = 'jpeg';
			break;
		case IMAGETYPE_GIF:
			$typestr = 'gif';
			break;
		case IMAGETYPE_PNG:
			$typestr = 'png';
			break;
		default:
			break;
	}

	$function = "imagecreatefrom$typestr";
	$src_resource = $function($source_path);

	if(!$new_height)
	{
		$new_height = round($new_width * $old_height / $old_width);
	}
	elseif(!$new_width)
	{
		$new_width = round($new_height * $old_width / $old_height);
	}

	$destination_resource = imagecreatetruecolor($new_width, $new_height);

	imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

	if($type == 2)
	{
		imageinterlace($destination_resource, 1);
		imagejpeg($destination_resource, $destination_path, $quality);
	}
	else
	{
		$function = "image$typestr";
		$function($destination_resource, $destination_path);
	}

	imagedestroy($destination_resource);
	imagedestroy($src_resource);
}

function image_resize_h($source_path, $destination_path, $new_height, $quality = FALSE, $new_width = FALSE)
{
	ini_set("gd.jpeg_ignore_warning", 1);

	list($old_width, $old_height, $type) = getimagesize($source_path);

	switch($type)
	{
		case IMAGETYPE_JPEG:
			$typestr = 'jpeg';
			break;
		case IMAGETYPE_GIF:
			$typestr = 'gif';
			break;
		case IMAGETYPE_PNG:
			$typestr = 'png';
			break;
		default:
			break;
	}

	$function = "imagecreatefrom$typestr";
	$src_resource = $function($source_path);

	if(!$new_height)
	{
		$new_height = round($new_width * $old_height / $old_width);
	}
	elseif(!$new_width)
	{
		$new_width = round($new_height * $old_width / $old_height);
	}

	$destination_resource = imagecreatetruecolor($new_width, $new_height);

	imagecopyresampled($destination_resource, $src_resource, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_height);

	if($type == 2)
	{
		imageinterlace($destination_resource, 1);
		imagejpeg($destination_resource, $destination_path, $quality);
	}
	else
	{
		$function = "image$typestr";
		$function($destination_resource, $destination_path);
	}

	imagedestroy($destination_resource);
	imagedestroy($src_resource);
}

function randomName($tmp_name)
{
	$name = md5(md5($tmp_name.date('d-m-Y H-i-s')).rand(0, 1000000));
	return $name;
}

/* функция для обработки дополнительных фотографий */

function resize($image, $w_o = false, $h_o = false)
{
	if (($w_o < 0) || ($h_o < 0)) {
		echo "Некорректные входные параметры";
		return false;
	}

	list($w_i, $h_i, $type) = getimagesize($image);

	$types = array("", "gif", "jpeg", "png");
	$ext = $types[$type]; // Зная "числовой" тип изображения, узнаём название типа

	if ($ext) {
		$func = 'imagecreatefrom' . $ext; // Получаем название функции, соответствующую типу, для создания изображения
		$img_i = $func($image); // Создаём дескриптор для работы с исходным изображением
	} else {
		echo 'Некорректное изображение'; // Выводим ошибку, если формат изображения недопустимый

		return false;
	}

	/* Если указать только 1 параметр, то второй подстроится пропорционально */
	if (!$h_o) $h_o = $w_o / ($w_i / $h_i);
	if (!$w_o) $w_o = $h_o / ($h_i / $w_i);

	$img_o = imagecreatetruecolor($w_o, $h_o); // Создаём дескриптор для выходного изображения

	imagecopyresampled($img_o, $img_i, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i); // Переносим изображение из исходного в выходное, масштабируя его

	$func = 'image' . $ext; // Получаем функция для сохранения результата

	return $func($img_o, $image); // Сохраняем изображение в тот же файл, что и исходное, возвращая результат этой операции
}