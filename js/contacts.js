/**
 * Created by jeyfost on 07.08.2017.
 */

function sendMessage() {
	var name = $('#nameInput').val();
	var email = $('#emailInput').val();
	var phone = $('#phoneInput').val();
	var date = $('#dateInput').val();
	var message = $('#messageInput').val();

	if(name !== '') {
		if(email !== '') {
			$.ajax({
				type: "POST",
				data: {"email": email},
				url: "/scripts/reviews/ajaxValidateEmail.php",
				success: function (validity) {
					if(validity === "valid") {
						if(message !== '') {
							$.ajax({
								type: "POST",
								data: {
									"name": name,
									"email": email,
									"phone": phone,
									"date": date,
									"message": message
								},
								url: "/scripts/contacts/ajaxSendMessage.php",
								beforeSend: function () {
									$.notify("Ваше сообщение отправляется...", "info");
								},
								success: function (response) {
									switch (response) {
										case "ok":
											$.notify("Ваше сообщение успешно отправлено. Я скоро вам отвечу!", "success");

											$('#nameInput').val('');
											$('#emailInput').val('');
											$('#phoneInput').val('');
											$('#dateInput').val('');
											$('#messageInput').val('');
											break;
										case "failed":
											$.notify("при отправке сообщения произошла ошибка. Попробуйте снова.", "error");
											break;
										default:
											$.notify(response, "warn");
											break;
									}
								}
							});
						} else {
							$.notify("Вы не ввели текст сообщения", "error");
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