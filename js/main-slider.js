/**
 * Created by jeyfost on 31.07.2017.
 */

$(window).on("load", function() {
	$('#slider').height(parseInt($(window).height() - $('#slider').offset().top));
});

var slides = document.querySelectorAll('#slider .slide');
var currentSlide = 0;
var slideInterval = setInterval(nextSlide, 6000);

function nextSlide() {
	if(slides[currentSlide].firstChild.height < $('#slider').height()) {
		slides[currentSlide].firstChild.style.height = $('#slider').height() + 'px';
	}

    slides[currentSlide].className = 'slide';
    slides[currentSlide].style.top = $('#slider').offset().top;
    currentSlide = (currentSlide+1)%slides.length;
    slides[currentSlide].className = 'slide showing';
}