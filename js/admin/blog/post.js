/**
 * Created by jeyfost on 21.08.2017.
 */

function addPost(id) {
	var name = $('#nameInput').val();
	var link = $('#linkInput').val();
	var description = $('#descriptionInput').val();
	var text = document.getElementsByTagName("iframe")[0].contentDocument.getElementsByTagName("body")[0].innerHTML;
	var tags = $('#tagsInput').val();
	var formData = new FormData($('#addForm').get(0));

	formData.append("subcategory_id", id);
	formData.append("text", text);

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