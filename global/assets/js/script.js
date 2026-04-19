//Menu 
$(document).ready(function(){ 
	var touch 	= $('#resp-menu');
	var menu 	= $('.menu');
 
	$(touch).on('click', function(e) {
		e.preventDefault();
		menu.slideToggle();
	});
	
	$(window).resize(function(){
		var w = $(window).width();
		if(w > 767 && menu.is(':hidden')) {
			menu.removeAttr('style');
		}
	});
	
});
//Service
$(document).ready(function(){
		$("#meal").mouseenter(function(){
			$("#meal_text").show(1000);
		});
		$("#meal").mouseleave(function(){
			$("#meal_text").hide(1000);
		});
		
		$("#language").mouseenter(function(){
			$("#language-text").show(1000);
		});
		$("#language").mouseleave(function(){
			$("#language-text").hide(1000);
		});
		
		$("#transportation").mouseenter(function(){
			$("#transportation-text").show(1000);
		});
		$("#transportation").mouseleave(function(){
			$("#transportation-text").hide(1000);
		});
		
		$("#day").mouseenter(function(){
			$("#day-text").show(1000);
		});
		$("#day").mouseleave(function(){
			$("#day-text").hide(1000);
		});
		
		$("#special").mouseenter(function(){
			$("#special-text").show(1000);
		});
		$("#special").mouseleave(function(){
			$("#special-text").hide(1000);
		});
		
		$("#education").mouseenter(function(){
			$("#education-text").show(1000);
		});
		$("#education").mouseleave(function(){
			$("#education-text").hide(1000);
		});
});

//Start Carousel 
$(window).load(function () {               
	$("#flexiselDemo3").flexisel({
		visibleItems: 4,
		itemsToScroll: 1,
		autoPlay: {
			enable: true,
			interval: 5000,
			pauseOnHover: true
		}
	});

});

//End Carosel


//Start Gallery
$(document).ready(function () {
	$('.gallery').featherlightGallery({
		gallery: {
			fadeIn: 300,
			fadeOut: 300
		},
		openSpeed: 300,
		closeSpeed: 300
	});
});

(function (i, s, o, g, r, a, m) {
	i['GoogleAnalyticsObject'] = r;
	i[r] = i[r] || function () {
		(i[r].q = i[r].q || []).push(arguments)
	}, i[r].l = 1 * new Date();
	a = s.createElement(o),
			m = s.getElementsByTagName(o)[0];
	a.async = 1;
	a.src = g;
	m.parentNode.insertBefore(a, m)
})(window, document, 'script', '//stats.g.doubleclick.net/dc.js', 'ga');

ga('create', 'UA-5342062-6', 'noelboss.github.io');
ga('send', 'pageview');

//End Gallery

 //Start Parents Testimonial 
$(document).ready(function () {
	$('.flexslider').flexslider({
		animation: 'fade',
		controlsContainer: '.flexslider'
	});
});

//End Parents Testimonial

//counter 
 jQuery(document).ready(function ($) {
	$('.counter').counterUp({
		delay: 10,
		time: 1500
	});
});
