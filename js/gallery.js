/**
 * Created by jeyfost on 23.08.2017.
 */

function likePost(id) {
	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/gallery/ajaxLike.php",
		success: function (response) {
			switch (response) {
				case "ok":
					$('#like' + id).css("color", "#b21c1c");
					$('#like' + id).css("cursor", "default");
					$('#like' + id).attr("onclick", "");

					$.ajax({
						type: "POST",
						data: {"id": id},
						url: "/scripts/gallery/ajaxLikesCount.php",
						success: function (count) {
							$('#likesCount' + id).css("opacity", "0");

							setTimeout(function () {
								$('#likesCount' + id).html(count);
								$('#likesCount' + id).css("opacity", "1");
							}, 100)
						}
					});
					break;
				case "failed":
					$.notify("Не удалось поставить лайк. Попробуйте снова.", "error");
					break;
				default:
					$.notify(response, "warn");
					break;
			}
		}
	});
}

function showShareBlock(id, action) {
	if(action === 1) {
		$('#shareBlock' + id).css("opacity", 1);
	} else {
		$('#shareBlock' + id).css("opacity", 0);
	}
}

function fontColor(icon, text, action) {
	if(action === 1) {
		$('#' + icon).css('color', '#e0c1ac');
		$('#' + text).css('color', '#e0c1ac');
	} else {
		$('#' + icon).css('color', '#4c4c4c');
		$('#' + text).css('color', '#4c4c4c');
	}
}

function leaveComment(id) {
	var name = $('#nameInput').val();
	var text = $('#textInput').val();

	var formData = new FormData($('#commentForm').get(0));
	formData.append("id", id);

	if(name !== '') {
		if(text !== '') {
			$.ajax({
				type: "POST",
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				url: "/scripts/gallery/ajaxComment.php",
				success: function (response) {
					switch(response) {
						case "ok":
							$.notify("Комментарий успешно добавлен.", "success");

							$.ajax({
								type: "POST",
								data: {"id": id},
								url: "/scripts/gallery/ajaxRebuildCommentsContainer.php",
								success: function (comments) {
									$('#commentsContainer').css("opacity", "0");
									$('#nameInput').val('');
									$('#textInput').val('');

									setTimeout(function () {
										$('#commentsContainer').html(comments);
										$('#commentsContainer').css("opacity", "1");
									}, 300);
								}
							});
							break;
						case "failed":
							$.notify("Произошла ошибка. Попробуйте снова.", "error");
							break;
						case "captcha":
							$.notify("Вы не прошли проверку на робота.", "error");
							break;
						default:
							$.notify(response, "warn");
							break;
					}
				}
			});
		} else {
			$.notify("Введите текст комментария.", "error");
		}
	} else {
		$.notify("Введите ваше имя.", "error");
	}
}