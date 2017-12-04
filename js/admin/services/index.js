/**
 * Created by jeyfost on 09.11.2017.
 */

function editService() {
	var name = $('#serviceNameInput').val();
	var description = $('#serviceDescriptionInput').val();
	var list = $('#serviceListInput').val();
	var formData = new FormData($('#serviceForm').get(0));

	if(name !== '') {
		if(description !== '') {
			if(list !== '') {
				$.ajax({
					type: "POST",
					data: formData,
					dataType: "json",
					processData: false,
					contentType: false,
					url: "/scripts/admin/services/ajaxEditService.php",
					beforeSend: function () {
						$.notify("Идёт редактирование услуги...", "info");
					},
					success: function (response) {
						switch(response) {
							case "ok":
								$.notify("Информация об услуге была успешно изменена.", "success");
								break;
							case "failed":
								$.notify("Произошла ошибка. Попробуйте снова.", "error");
								break;
							case "duplicate":
								$.notify("Услуга с таким названием уже существует.", "error");
								break;
							case "photo":
								$.notify("Выбрано изображение неподходящего формата.", "error");
								break;
							case "photo_failed":
								$.notify("При загрузке фотографии произошла ошибка. Попробуйте снова.", "error");
								break;
							default:
								$.notify(response, "warn");
								break;
						}
					}
				});
			} else {
				$.notify("Необходимо ввести пункты услуги.", "error");
			}
		} else {
			$.notify("Необходимо ввести описание услуги", "error");
		}
	} else {
		$.notify("Необходимо ввести название услуги", "error");
	}
}

function deleteService() {
	if(confirm("Вы действительно хотите удалить эту услугу?")) {
		var formData = new FormData($('#serviceForm').get(0));

		$.ajax({
			type: "POST",
			data: formData,
			dataType: "json",
			processData: false,
			contentType: false,
			url: "/scripts/admin/services/ajaxDeleteService.php",
			beforeSend: function () {
				$.notify("Идёт удаление услуги...", "info");
			},
			success: function (response) {
				setTimeout(function () {
					switch(response) {
						case "ok":
							$.notify("Услуга была успешно удалена.", "success");
							setTimeout(function () {
								window.location.href = "/admin/services/index.php";
							}, 3000);
							break;
						case "failed":
							$.notify("Произошла ошибка. Попробуйте снова.", "error");
							break;
						default:
							$.notify(response, "warn");
							break;
					}
				}, 1500);
			}
		});
	}
}