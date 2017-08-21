/**
 * Created by jeyfost on 18.08.2017.
 */

function addSubcategory() {
	var name = $('#nameInput').val();
	var priority = $('#prioritySelect').val();
	var link = $('#linkInput').val();
	var title = $('#titleInput').val();
	var keywords = $('#keywordsInput').val();
	var description = $('#descriptionInput').val();

	if(name !== '') {
		if(link !== '') {
			if(title !== '') {
				$.ajax({
					type: "POST",
					data: {
						"name": name,
						"priority": priority,
						"link": link,
						"title": title,
						"keywords": keywords,
						"description": description
					},
					url: "/scripts/admin/blog/ajaxAddSubcategory.php",
					success: function(response) {
						switch(response) {
							case "ok":
								$.notify("Раздел был добавлен.", "success");
								break;
							case "failed":
								$.notify("Во время добавления раздела произошла ошибка. Попробуйте снова.", "error");
								break;
							case "link":
								$.notify("Введённый адрес ссылки уже существует.", "error");
								break;
							default:
								$.notify(response, "warn");
								break;
						}
					}
				});
			} else {
				$.notify("Вы не ввели заголовок.", "error");
			}
		} else {
			$.notify("Вы не ввели адрес ссылки.", "error");
		}
	} else {
		$.notify("Вы не ввели название раздела.", "error");
	}
}

function editSubcategory(id) {
	var name = $('#nameInput').val();
	var priority = $('#prioritySelect').val();
	var link = $('#linkInput').val();
	var title = $('#titleInput').val();
	var keywords = $('#keywordsInput').val();
	var description = $('#descriptionInput').val();

	if(name !== '') {
		if(link !== '') {
			if(title !== '') {
				$.ajax({
					type: "POST",
					data: {
						"name": name,
						"priority": priority,
						"link": link,
						"title": title,
						"keywords": keywords,
						"description": description,
						"id": id
					},
					url: "/scripts/admin/blog/ajaxEditSubcategory.php",
					success: function(response) {
						switch(response) {
							case "ok":
								$.notify("Раздел был успешно отредактирован.", "success");
								break;
							case "failed":
								$.notify("Во время редактирования раздела произошла ошибка. Попробуйте снова.", "error");
								break;
							case "link":
								$.notify("Введённый адрес ссылки уже существует.", "error");
								break;
							default:
								$.notify(response, "warn");
								break;
						}
					}
				});
			} else {
				$.notify("Вы не ввели заголовок.", "error");
			}
		} else {
			$.notify("Вы не ввели адрес ссылки.", "error");
		}
	} else {
		$.notify("Вы не ввели название раздела.", "error");
	}
}