/**
 * Created by jeyfost on 22.08.2017.
 */

function deletePost(id, subcategory_id) {
	if(confirm("Вы действительно хотите удалить запись?")) {
		$.ajax({
			type: "POST",
			data: {"id": id},
			url: "/scripts/admin/blog/ajaxDeletePost.php",
			success: function (response) {
				switch(response) {
					case "ok":
						$.notify("Запись успешно удалена.", "success");

						$.ajax({
							type: "POST",
							data: {"id": subcategory_id},
							url: "/scripts/admin/blog/ajaxRebuildPostsTable.php",
							success: function (table) {
								$('#galleryTable').css("opacity", "0");

								setTimeout(function () {
									$('#galleryTable').html(table);
									$('#galleryTable').css("opacity", 1);
								}, 300);
							}
						});
						break;
					case "failed":
						$.notify("При удалении записи произошла ошибка. Попробуйте снова.", "error");
						break;
					default:
						$.notify(response, "warn")
						break;
				}
			}
		});
	}
}