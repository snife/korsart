/**
 * Created by jeyfost on 04.08.2017.
 */

function sendReview() {
	var inst = $('[data-remodal-id=modal]').remodal();

	var name = $('#nameInput').val();
	var email = $('#emailInput').val();
	var text = $('#textInput').val();

	var formData = new FormData($('#modalForm').get(0));

	if(name !== '') {
		if(email !== '') {
			$.ajax({
				type: "POST",
				data: {"email": email},
				url: "/scripts/reviews/ajaxValidateEmail.php",
				success: function (validity) {
					if(validity === "valid") {
						if(text !== '') {
							$.ajax({
								type: "POST",
								dataType: "json",
								processData: false,
								contentType: false,
								data: formData,
								url: "/scripts/reviews/ajaxAddReview.php",
								beforeSend: function () {
									$.notify("Ваш отзыв отправляется...", "info");
								},
								success: function (response) {
									switch(response) {
										case "ok":
											$.notify("Спасибо! Отзыв был успешно отправлен.", "success");
											inst.close();
											break;
										case "failed":
											$.notify("Во время отправления отзыва произошла ошибка. Попробуйте снова.", "error");
											break;
										case "no photo":
											$.notify("Вы не добавили фотографию", "error");
											break;
										case "file type":
											$.notify("Выбранный файл не является фотографией.", "error");
											break;
										default:
											$.notify(response, "warn");
											break;
									}
								}
							});
						} else {
							$.notify("Вы не ввели текст отзыва", "error");
						}
					} else {
						$.notify("Вы ввели email неверного формата", "error");
					}
				}
			});
		} else {
			$.notify("Вы не ввели email", "error");
		}
	} else {
		$.notify("Вы не ввели имя", "error");
	}
}

function changeNewReviewButton(action) {
	if(action === 1) {
		$('#newReviewButton').css('background-color', '#e6e6e6');
		$('#newReviewButton').css('color', '#474747');
	} else {
		$('#newReviewButton').css('background-color', '#fff');
		$('#newReviewButton').css('color', '#000');
	}
}