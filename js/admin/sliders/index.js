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
