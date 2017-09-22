/**
 * Created by jeyfost on 22.09.2017.
 */

function edit() {
	var oldLogin = $('#oldLoginInput').val();
	var oldPassword = $('#oldPasswordInput').val();
	var newLogin = $('#newLoginInput').val();
	var newPassword = $('#newPasswordInput').val();

	if(oldLogin !== '') {
		if(oldPassword !== '') {
			if(newLogin !== '' || newPassword !== '') {
				$.ajax({
					type: "POST",
					data: {
						oldLogin: oldLogin,
						oldPassword: oldPassword,
						newLogin: newLogin,
						newPassword: newPassword
					},
					url: "/scripts/admin/security/ajaxEditAdminData.php",
					success: function (response) {
						switch (response) {
							case "ok":
								$('#oldLoginInput').val('');
								$('#oldPasswordInput').val('');
								$('#newLoginInput').val('');
								$('#newPasswordInput').val('');

								$.notify("Данные учётной записи администратора были изменены.", "success");
								break;
							case "failed":
								$.notify("Во время изменения данных учётной записи администратора произошла ошибка. Попробуйте снова.", "error");
								break;
							case "same login":
								$.notify("Новый логин совпадает со старым.", "error");
								break;
							case "same password":
								$.notify("Новый пароль совпадает со старым.", "error");
								break;
							case  "old":
								$.notify("Старый логин или пароль не верны.", "error");
								break;
							default:
								$.notify(response, "warn");
								break;
						}
					}
				});
			}
		} else {
			$.notify("Вы не ввели старый пароль.", "error");
		}
	} else {
		$.notify("Вы не ввели старый логин.", "error");
	}
}