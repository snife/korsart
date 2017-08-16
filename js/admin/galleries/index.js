/**
 * Created by jeyfost on 16.08.2017.
 */

function loadGalleryText(id) {
	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/admin/galleries/ajaxLoadGalleryText.php",
		success: function (response) {
			CKEDITOR.instances["textInput"].setData(response);
		}
	});
}