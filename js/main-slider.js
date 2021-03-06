/**
 * Created by jeyfost on 31.07.2017.
 */

var slides = document.querySelectorAll('#slider .slide');
var currentSlide = 0;
var slideInterval = setInterval(nextSlide, 3500);

$(window).on("load", function() {
	if($('*').is('#mainIMG')) {
		if($(document).width() < 768) {
			$('#slider').height(parseInt($('#mainIMG').height()));
		} else {
			if($('#mainIMG').height() > parseInt($(window).height() - $('header').height() - 93)) {
				$('#slider').height(parseInt($(window).height() - $('header').height() - 93));
			} else {
				$('#mainIMG').css("width", "auto");
				$('#slider').height(parseInt($(window).height() - $('header').height() - 93));
				$('#mainIMG').css("height", "100%");
			}
		}
	} else {
		$('#slider').height(parseInt($(window).height() - $('#slider').offset().top));

		if($(document).width() < 768) {
			var minHeight = slides[0].firstChild.height;

			for(var i = 1; i < slides.length; i++) {
				if(slides[i].firstChild.height < minHeight) {
					minHeight = slides[i].firstChild.height;
				}
			}

			$('#slider').height(minHeight);
		}
	}
});

function nextSlide() {
	if(slides[currentSlide].firstChild.height < $('#slider').height()) {
		slides[currentSlide].firstChild.style.height = $('#slider').height() + 'px';
	}

    slides[currentSlide].className = 'slide';
    slides[currentSlide].style.top = $('#slider').offset().top;
    currentSlide = (currentSlide+1)%slides.length;
    slides[currentSlide].className = 'slide showing';
}