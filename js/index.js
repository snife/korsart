/**
 * Created by jeyfost on 02.08.2017.
 */

function reviewsButton(action) {
	if(action === 1) {
		document.getElementById('allReviewsButton').style.backgroundColor = "#e3e3e3";
		document.getElementById('allReviewsButton').style.color = "#4651ed";
	} else {
		document.getElementById('allReviewsButton').style.backgroundColor = "#457bdf";
		document.getElementById('allReviewsButton').style.color = "#fff";
	}
}

function servicesButton(action) {
	if(action === 1) {
		document.getElementById('servicesButton').style.backgroundColor = "#e3e3e3";
		document.getElementById('servicesButton').style.color = "#4651ed";
	} else {
		document.getElementById('servicesButton').style.backgroundColor = "#457bdf";
		document.getElementById('servicesButton').style.color = "#fff";
	}
}