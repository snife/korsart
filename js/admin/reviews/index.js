/**
 * Created by jeyfost on 14.08.2017.
 */

function showReview(id, button) {
	var checked = 0;

	if(document.getElementById(button).checked) {
		checked = 1;
	}

	$.ajax({
		type: "POST",
		data: {
			"id": id,
			"checked": checked
		},
		url: "/scripts/admin/reviews/ajaxShowReview.php",
		success: function (response) {
			switch(response) {
				case "on":
					$.notify("Отображение отзыва включено.", "success");
					break;
				case "off":
					$.notify("Отображение отзыва отключено.", "info");
					break;
				case "failed":
					$.notify("Произошла ошибка. Попробуйте снова.", "error");
					break;
				default:
					$.notify(response, "warn");
					break;
			}
		}
	});
}

function editReview(id) {
	var name = $('#nameInput').val();
	var text = $('#textInput').val();
	var formData = new FormData($('#reviewEditForm').get(0));

	formData.append("id", id);

	if(name !== '') {
		if(text !== '') {
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				url: "/scripts/admin/reviews/ajaxEditReview.php",
				beforeSend: function() {
					$.notify("Отзыв редактируется...", "info");
				},
				success: function (response) {
					switch(response) {
						case "ok":
							$.notify("Редактирование отзыва прошло успешно.", "success");
							break;
						case "failed":
							$.notify("Во время редактирования отзыва произошла ошибка. Попробуйте снова.", "error");
							break;
						case "photo":
							$.notify("Вы выбрали файл недопустимого формата.", "error");
							break;
						case "photo failed":
							$.notify("Отзыв был обновлён, однако новая фотография не была загружена.", "warn");
							break;
						default:
							$.notify(response, "warn");
							break;
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					$.notify(textStatus + "; " + errorThrown);
				}
			});
		} else {
			$.notify("Вы не ввели текст отзыва.", "error");
		}
	} else {
		$.notify("Вы не ввели имя.", "error");
	}
}

function deleteReview(id) {
	if(confirm("Вы действительно хотите удалить отзыв?")) {
		$.ajax({
			type: "POST",
			data: {"id": id},
			url: "/scripts/admin/reviews/ajaxDeleteReview.php",
			success: function (response) {
				switch(response) {
					case "ok":
						$.notify("Отзыв был удалён.", "success");

						$.ajax({
							type: "POST",
							url: "/scripts/admin/reviews/ajaxRebuildReviewsContainer.php",
							success: function (reviews) {
								$('#reviews').css("opacity", 0);
								setTimeout(function () {
									$('#reviews').html(reviews);
									$('#reviews').css("opacity", 1);
								}, 300);
							}
						});
						break;
					case "failed":
						$.notify("При удалении отзыва произошла ошибка. Попробуйте снова.", "error");
						break;
					default:
						$.notify(response, "warn");
						break;
				}
			}
		});
	}
}