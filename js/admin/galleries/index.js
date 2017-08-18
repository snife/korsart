/**
 * Created by jeyfost on 16.08.2017.
 */

function loadGalleryText(id) {
	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/admin/galleries/ajaxLoadGalleryText.php",
		success: function (response) {
			CKEDITOR.instances["textInput"].setData(response);
		}
	});
}

function editGallery(id) {
	var name = $('#nameInput').val();
	var link = $('#linkInput').val();
	var title = $('#titleInput').val();
	var text = document.getElementsByTagName("iframe")[0].contentDocument.getElementsByTagName("body")[0].innerHTML;
	var formData = new FormData($('#editForm').get(0));

	formData.append("subcategory_id", id);
	formData.append("text", text);

	if(name !== '') {
		if(link !== '') {
			if(title !== '') {
				if(text !== '' && text !== '<p><br></p>') {
					$.ajax({
						type: "POST",
						data: formData,
						processData: false,
						contentType: false,
						dataType: "json",
						url: "/scripts/admin/galleries/ajaxEditGallery.php",
						beforeSend: function () {
							$.notify("Идёт редактирование галереи...", "info");
						},
						success: function (response) {
							switch(response) {
								case "ok":
									$.notify("Редактирование прошло успешно.", "success");

									if($('#galleryPhotos')) {
										$.ajax({
											type: "POST",
											data: {"id": id},
											url: "/scripts/admin/galleries/ajaxRebuildPhotosContainer.php",
											success: function (photos) {
												$('#galleryPhotos').css('opacity', 0);

												setTimeout(function () {
													$('#galleryPhotos').html(photos);
													$('#galleryPhotos').css('opacity', 1);
												}, 300);
											}
										});
									}
									break;
								case "failed":
									$.notify("Во время редактирования произошла ошибка. Повторите попытку.", "error");
									break;
								case "photo type":
									$.notify("Выбранная фотография имеет недопустимый формат.", "error");
									break;
								case "photos type":
									$.notify("Некоторые из дополнительных фотографий имеют недопустимый формат.", "error");
									break;
								case "link":
									$.notify("Введённый вами адрес ссылки уже существует.", "error");
									break;
								default:
									$.notify(response, "warn");
									break;
							}
						}
					});
				} else {
					$.notify("Вы не ввели текст.", "error");
				}
			} else {
				$.notify("Вы не ввели заголовок.", "error");
			}
		} else {
			$.notify("Вы не ввели адрес ссылки.", "error");
		}
	} else {
		$.notify("Вы не ввели название галереи.", "error");
	}
}

function deleteGalleryPhoto(id, post_id) {
	if(confirm("Вы действительно хотите удалить фотографию?")) {
		$.ajax({
			type: "POST",
			data: {"id": id},
			url: "/scripts/admin/galleries/ajaxDeletePhoto.php",
			success: function (response) {
				switch (response) {
					case "ok":
						$.notify("Фотография была удалена.", "success");

						$.ajax({
							type: "POST",
							data: {"id": post_id},
							url: "/scripts/admin/galleries/ajaxRebuildPhotosContainer.php",
							success: function (photos) {
								$('#galleryPhotos').css('opacity', 0);

								setTimeout(function () {
									$('#galleryPhotos').html(photos);
									$('#galleryPhotos').css('opacity', 1);
								}, 300);
							}
						});
						break;
					case "failed":
						$.notify("Во время удаления фотографии проищошла ошибка. Попробуйте снова.", "error");
						break;
					case "id":
						$.notify("Фотографии с таким ID не существует.", "error");
						break;
					default:
						$.notify(response, "warn");
						break;
				}
			}
		})
	}
}

function deleteGallery(id, category_id) {
	if(confirm("Вы действительно хотите удалить галерею?")) {
		$.ajax({
			type: "POST",
			data: {"id": id},
			url: "/scripts/admin/galleries/ajaxDeleteGallery.php",
			success: function (response) {
				switch(response) {
					case "ok":
						$.notify("Галерея успешно удалена.", "success");

						$.ajax({
							type: "POST",
							data: {"category_id": category_id},
							url: "/scripts/admin/galleries/ajaxRebuildGalleryTable.php",
							success: function (table) {
								$('#galleryTable').css('opacity', '0');

								setTimeout(function () {
									$('#galleryTable').html(table);
									$('#galleryTable').css('opacity', 1);
								}, 300);
							}
						});
						break;
					case "failed":
						$.notify("Во время удаления галереи произошла ошибка. Попробуйте снова.", "error");
						break;
					case "id":
						$.notify("Галереи с таким ID не существует.", "error");
						break;
					default:
						$.notify(response, "warn");
						break;
				}
			}
		});
	}
}