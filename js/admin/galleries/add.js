/**
 * Created by jeyfost on 14.08.2017.
 */

function addGallery(id) {
	var name = $('#nameInput').val();
	var link = $('#sefLinkInput').val();
	var title = $('#titleInput').val();
	var text = document.getElementsByTagName("iframe")[0].contentDocument.getElementsByTagName("body")[0].innerHTML;
	var formData = new FormData($('#addForm').get(0));

	formData.append("text", text);
	formData.append("category_id", id);

	if(name !== '') {
		if(link !== '') {
			if(title !== '') {
				if(text !== '' && text !== '<p><br></p>') {
					$.ajax({
						type: "POST",
						data: formData,
						dataType: "json",
						processData: false,
						contentType: false,
						url: "/scripts/admin/galleries/ajaxAddGallery.php",
						beforeSend: function () {
							$.notify("Идёт создание галереи...", "info");
						},
						success: function (response) {
							switch (response) {
								case "ok":
									$.notify("Галерея успешно создана.", "success");
									break;
								case "failed":
									$.notify("При создании галереи произошла ошибка. Попробуйте снова.", "error");
									break;
								case "link":
									$.notify("Указанный вами адрес ссылки уже существует.", "error");
									break;
								case "photo":
									$.notify("Вы не добавили заглавную фотографию.", "error");
									break;
								case "photo format":
									$.notify("Выбранный вами файл имеет недопустимый формат.", "error");
									break;
								case "photos":
									$.notify("Вы не добавили фотографии.", "error");
									break;
								case "photos format":
									$.notify("Некоторые фотографии имеют недопустимый формат.", "error");
									break;
								default:
									$.notify(response, "warn");
									break;
							}
						}
					});
				}
			} else {
				$.notify("Вы не ввели заголовок для страницы с галереей.", "error");
			}
		} else {
			$.notify("Вы не ввели адрес ссылки.", "error");
		}
	} else {
		$.notify("Вы не ввели название галереи.", "error");
	}
}