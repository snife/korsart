/**
 * Created by jeyfost on 01.08.2017.
 */

$(window).on("load", function() {
	$('#gallery-slider').height($('#slider').height());
});

var g_slides = document.querySelectorAll('#gallery-slider .gallery-slide');
var g_currentSlide = 0;
var g_slideInterval = setInterval(galleryNextSlide, 5000);

function galleryNextSlide() {
	if(g_slides[g_currentSlide].firstChild.height < $('#gallery-slider').height()) {
		g_slides[g_currentSlide].firstChild.style.height = $('#gallery-slider').height() + 'px';
	}

    g_slides[g_currentSlide].className = 'gallery-slide';
    g_slides[g_currentSlide].style.top = $('#gallery-slider').offset().top;
    g_currentSlide = (g_currentSlide+1)%g_slides.length;
    g_slides[g_currentSlide].className = 'gallery-slide showing';
}