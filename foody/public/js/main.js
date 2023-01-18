/*
Template Name: Osahan Home Construction | Bootstrap Responsive Website Template
Author: askbootstrap
Author URI: https://themeforest.net/user/askbootstrap
Version: 1.0
*/

/*
-- Menu
-- Back To Top
-- Tooltip
-- Preloader
-- Animation Js
-- Carousel Speed
*/

(function($) {
	'use strict';
	jQuery(document).on('ready', function(){
	
	
		// ===========Menu============
		$('a.page-scroll').on('click', function(e){
				var anchor = $(this);
				$('html, body').stop().animate({
					scrollTop: $(anchor.attr('href')).offset().top - 0
				}, 1500);
				e.preventDefault();
			});		

			$(window).on('scroll', function() {
			  if ($(this).scrollTop() > 20) {
				$('.menu-top').addClass('menu-shrink');
			  } else {
				$('.menu-top').removeClass('menu-shrink');
			  }
			});
			
			$(document).on('click','.navbar-collapse.in',function(e) {
			if( $(e.target).is('a') && $(e.target).attr('class') != 'dropdown-toggle' ) {
				$(this).collapse('hide');
			}
		});				
		
		
		// ===========Back To Top============	
		$(window).scroll(function () {
			if ($(this).scrollTop() > 200) {
				 $('#back-to-top').fadeIn();
			} else {
				 $('#back-to-top').fadeOut();
			}
		});
		$('#back-to-top').on('click', function () {
			 $('#back-to-top').tooltip('hide');
			 $('body,html').animate({
				  scrollTop: 0
			 }, 800);
			 return false;
		});
		$('#back-to-top').tooltip('hide');
		
		
		// ===========Tooltip============
		$('[data-toggle="tooltip"]').tooltip();
				
				
		// ===========Preloader============
		$(window).on('load', function(){
			$('#preloader').fadeOut('slow',function(){$(this).remove();});
		});
		
		
		// ===========Animation Js============
		new WOW().init();	
		
		
		// ===========Carousel Speed============
		$('.carousel').carousel({
			interval: 4000
	    });
		
	}); 	
	
})(jQuery);