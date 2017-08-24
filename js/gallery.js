/**
 * Created by jeyfost on 23.08.2017.
 */

function likePost(id) {
	$.ajax({
		type: "POST",
		data: {"id": id},
		url: "/scripts/gallery/ajaxLike.php",
		success: function (response) {
			switch (response) {
				case "ok":
					$('#like' + id).css("color", "#b21c1c");
					$('#like' + id).css("cursor", "default");
					$('#like' + id).attr("onclick", "");

					$.ajax({
						type: "POST",
						data: {"id": id},
						url: "/scripts/gallery/ajaxLikesCount.php",
						success: function (count) {
							$('#likesCount' + id).css("opacity", "0");

							setTimeout(function () {
								$('#likesCount' + id).html(count);
								$('#likesCount' + id).css("opacity", "1");
							}, 100)
						}
					});
					break;
				case "failed":
					$.notify("Не удалось поставить лайк. Попробуйте снова.", "error")
					break;
				default:
					$.notify(response, "warn");
					break;
			}
		}
	});
}

function showShareBlock(id, action) {
	if(action === 1) {
		$('#shareBlock' + id).css("opacity", 1);
	} else {
		$('#shareBlock' + id).css("opacity", 0);
	}
}