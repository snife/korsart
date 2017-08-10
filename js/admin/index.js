/**
 * Created by jeyfost on 10.08.2017.
 */

function login() {
	var login = $('#loginInput').val();
	var password = $('#passwordInput').val();

	if(login !== '') {
		if(password !== '') {
			$.ajax({
				type: "POST",
				data: {"login": login, "password": password},
				url: "/scripts/admin/ajaxLogin.php",
				success: function (response) {
					switch (response) {
						case "ok":
							window.location.href = "admin.php";
							break;
						case "failed":
							$.notify("Неверно введён логин или пароль.", "error");
							break;
						default:
							$.notify(response, "warn");
							break;
					}
				}
			});
		} else {
			$.notify("Вы не ввели пароль", "error");
		}
	} else {
		$.notify("Вы не ввели логин", "error");
	}
}