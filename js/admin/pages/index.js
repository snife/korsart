/**
 * Created by jeyfost on 11.08.2017.
 */

function edit() {
	var id = $('#pageSelect').val();
	var title = $('#titleInput').val();
	var keywords = $('#keywordsInput').val();
	var description = $('#descriptionInput').val();

	if(title !== '') {
		$.ajax({
			type: "POST",
			data: {
				"id": id,
				"title": title,
				"keywords": keywords,
				"description": description
			},
			url: "/scripts/admin/pages/ajaxEditPage.php",
			beforeSend: function () {
				$.notify("Идёт обновление...", "info");
			},
			success: function (response) {
				switch(response) {
					case "ok":
						$.notify("Инофрмация о странице успешно изменена.", "success");
						break;
					case "failed":
						$.notify("При обновлении информации о странице произошла ошибка. Попробуйте снова.", "error");
						break;
					default:
						$.notify(response, "warn");
						break;
				}
			}
		});
	} else {
		$.notify("Вы не ввели заголовок страницы", "error");
	}
}