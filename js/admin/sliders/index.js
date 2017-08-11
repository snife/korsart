/**
 * Created by jeyfost on 11.08.2017.
 */

function deletePhoto(id, tableName) {
	if(confirm("Вы действительно хотите удалить фотографию?")) {
		$.ajax({
			type: "POST",
			data: {
				"id": id,
				"tableName": tableName
			},
			url: "/scripts/admin/sliders/ajaxDeleteSliderPhoto.php",
			beforeSend: function () {
				$.notify("Идёт удаление...", "info");
			},
			success: function (response) {
				switch (response) {
					case "ok":
						$.notify("Фотография усфпешно удалена.", "success");

						$.ajax({
							type: "POST",
							data: {"tableName": tableName},
							url: "/scripts/admin/sliders/ajaxRebuildPhotosContainer.php",
							success: function(photos) {
								$('#sliderPhotosContainer').css("opacity", "0");

								setTimeout(function () {
									$('#sliderPhotosContainer').html(photos);
									$('#sliderPhotosContainer').css("opacity", "1");
								}, 300);
							}
						});
						break;
					case "failed":
						$.notify("При удалении фотографии произошла ошибка. Попробуйте снова.", "error");
						break;
					default:
						$.notify(response, "warn");
						break;
				}
			}
		});
	}
}

function add(tableName) {
	var formData = new FormData($('#addForm').get(0));
	formData.append("tableName", tableName);

	$.ajax({
		type: "POST",
		data: formData,
		dataType: "json",
		processData: false,
		contentType: false,
		url: "/scripts/admin/sliders/ajaxAddSliderPhotos.php",
		beforeSend: function () {
			$.notify("Фотографии отправляются на сервер...", "info");
		},
		success: function (response) {
			switch (response) {
				case "ok":
					$.notify("Фотографии были успешно добавлены.", "success");

					$.ajax({
						type: "POST",
						data: {"tableName": tableName},
						url: "/scripts/admin/sliders/ajaxRebuildPhotosContainer.php",
						success: function (photos) {
							$('#sliderPhotosContainer').css("opacity", "0");

							setTimeout(function () {
								$('#sliderPhotosContainer').html(photos);
								$('#sliderPhotosContainer').css("opacity", "1");
							}, 300);
						}
					});
					break;
				case "failed":
					$.notify("При добавлении фотографий произошла ошибка. Попробуйте снова.", "error");
					break;
				case "files":
					$.notify("Некоторые или все файлы имеют недопустимый формат.", "error");
					break;
				case "partly":
					$.notify("Не все фотографии были добавлены.", "error");
					break;
				case "empty":
					$.notify("Вы не выбрали ни одной фотографии", "error");
					break;
				default:
					$.notify(response, "warn");
					break;
			}
		}
	})
}