/**
 * Created by jeyfost on 21.08.2017.
 */

function addPost(id) {
	var name = $('#nameInput').val();
	var link = $('#linkInput').val();
	var description = $('#descriptionInput').val();
	var text = document.getElementsByTagName("iframe")[0].contentDocument.getElementsByTagName("body")[0].innerHTML;
	var tags = $('#tagsInput').val();
	var draft = 0;

	if(document.getElementById('draftInput').checked) {
		draft = 1;
	}

	var formData = new FormData($('#addForm').get(0));
	formData.append("subcategory_id", id);
	formData.append("text", text);
	formData.append("is_draft", draft);

	if(name !== '') {
		if(link !== '') {
			if(description !== '') {
				if(text !== '' && text !== '<p><br></p>') {
					if(tags !== '') {
						$.ajax({
							type: "POST",
							data: formData,
							dataType: "json",
							processData: false,
							contentType: false,
							url: "/scripts/admin/blog/ajaxAddPost.php",
							beforeSend: function() {
								$.notify("Идёт добавление записи...", "info");
							},
							success: function (response) {
								switch(response) {
									case "ok":
										$.notify("Запись была успешно добавлена.", "success");
										break;
									case "failed":
										$.notify("В процессе добавления записи произошла ошибка. Попробуйте снова.", "error");
										break;
									case "link":
										$.notify("Введённыцй адрес ссылки уже существует.", "error");
										break;
									case "photo":
										$.notify("Вы не выбрали заглавную фотографию.", "error");
										break;
									case "photo type":
										$.notify("Заглавная фотография имеет недопустимый формат.", "error");
										break;
									case "photos":
										$.notify("Вы не добавили дополнительные фотографии.", "error");
										break;
									case "photos type":
										$.notify("Некоторые из дополнительных фотографий имеют недопустимый формат.", "error");
										break;
									default:
										break;
								}
							}
						});
					} else {
						$.notify("Вы не ввели теги для записи.", "error");
					}
				} else {
					$.notify("Вы не ввели текст.", "error");
				}
			} else {
				$.notify("Вы не ввели краткое описание.", "error");
			}
		} else {
			$.notify("Вы не ввели адрес ссылки.", "error");
		}
	} else {
		$.notify("Вы не ввели название.", "error");
	}
}

function loadText(id) {
	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/admin/blog/ajaxLoadText.php",
		success: function (response) {
			CKEDITOR.instances["textInput"].setData(response);
		}
	});
}

function deleteBlogPhoto(photo_id, post_id) {
	$.ajax({
		type: "POST",
		data: {"id": photo_id},
		url: "/scripts/admin/blog/ajaxDeletePhoto.php",
		success: function (response) {
			switch (response) {
				case "ok":
					$.notify('Фотогрфия успешно удалена.', 'success');

					$.ajax({
						type: "POST",
						data: {"id": post_id},
						url: "/scripts/admin/blog/ajaxRebuildPhotosContainer.php",
						success: function (photos) {
							$('#galleryPhotos').css('opacity', '0');

							setTimeout(function () {
								$('#galleryPhotos').html(photos);
								$('#galleryPhotos').css('opacity', '1');
							}, 300);
						}
					});
					break;
				case "failed":
					$.notify('Во время удаления фотографии произошла ошибка. Попробуйте снова.', 'success');
					break;
				default:
					$.notify(response, 'warn');
					break;
			}
		}
	});
}

function editPost(id) {
	var name = $('#nameInput').val();
	var link = $('#linkInput').val();
	var description = $('#descriptionInput').val();
	var text = document.getElementsByTagName("iframe")[0].contentDocument.getElementsByTagName("body")[0].innerHTML;
	var tags = $('#tagsInput').val();
	var draft = 0;

	if(document.getElementById('draftInput').checked) {
		draft = 1;
	}

	var formData = new FormData($('#editForm').get(0));
	formData.append("id", id);
	formData.append("text", text);
	formData.append("is_draft", draft);

	if(name !== '') {
		if(link !== '') {
			if(description !== '') {
				if(text !== '' && text !== '<p><br></p>') {
					if(tags !== '') {
						$.ajax({
							type: "POST",
							data: formData,
							dataType: "json",
							processData: false,
							contentType: false,
							url: "/scripts/admin/blog/ajaxEditPost.php",
							beforeSend: function() {
								$.notify("Идёт редактирование записи...", "info");
							},
							success: function (response) {
								switch(response) {
									case "ok":
										$.notify("Запись была успешно отредактирована.", "success");

										$.ajax({
											type: "POST",
											data: {"id": id},
											url: "/scripts/admin/blog/ajaxRebuildPhotosContainer.php",
											success: function (photos) {
												$('#galleryPhotos').css('opacity', '0');

												setTimeout(function () {
													$('#galleryPhotos').html(photos);
													$('#galleryPhotos').css('opacity', '1');
												}, 300);
											}
										});
										break;
									case "failed":
										$.notify("В процессе редактирования записи произошла ошибка. Попробуйте снова.", "error");
										break;
									case "link":
										$.notify("Введённыцй адрес ссылки уже существует.", "error");
										break;
									case "photo type":
										$.notify("Заглавная фотография имеет недопустимый формат.", "error");
										break;
									case "photos type":
										$.notify("Некоторые из дополнительных фотографий имеют недопустимый формат.", "error");
										break;
									case "photo failed":
										$.notify("При загрузке заглавной фотографии произошла ошибка. Попробуйте снова.", "error");
										break;
									default:
										break;
								}
							}
						});
					} else {
						$.notify("Вы не ввели теги для записи.", "error");
					}
				} else {
					$.notify("Вы не ввели текст.", "error");
				}
			} else {
				$.notify("Вы не ввели краткое описание.", "error");
			}
		} else {
			$.notify("Вы не ввели адрес ссылки.", "error");
		}
	} else {
		$.notify("Вы не ввели название.", "error");
	}
}

function setModalID(id) {
	$('#idInput').val(id);

	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/admin/blog/ajaxLoadPhoto.php",
		success: function (response) {
			$('#modalPhoto').attr('src', '/img/photos/blog/content/' + response);

			$.ajax({
				type: "POST",
				data: {"id": id},
				url: "/scripts/admin/blog/ajaxLoadDescription.php",
				success: function (text) {
					$('#photoDescriptionInput').val(text);
				}
			});
		}
	});
}

function addPhotoDescription() {
	var id = $('#idInput').val();
	var description = $('#photoDescriptionInput').val();

	if(id !== '') {
		if(description !== '') {
			$.ajax({
				type: "POST",
				data: {
					"id": id,
					"description": description
				},
				url: "/scripts/admin/blog/ajaxAddPhotoDescription.php",
				success: function (response) {
					if(response === "ok") {
						var inst = $('[data-remodal-id=modal]').remodal();
						inst.close();

						$('#photoDescriptionInput').val('');

						$.notify("Описание было добавлено.", "success")
					} else {
						$.notify(response);
					}
				}
			});
		} else {
			$.notify("Вы не ввели описание.", "error");
		}
	} else {
		$.notify("Вы не выбрали фотографию.", "error");
	}
}