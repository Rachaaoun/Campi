
(function() {
	"use strict";
	
	$(document).ready(function(){
		retinajs();
		stickyNavbarShrink('fixed');
		reponsiveNav('nav-menu');
		heightAdjust();

		$('[data-toggle="tooltip"]').tooltip({container: 'body'});
		$('.datepicker').datepicker();
		$('[data-toggle="popover"]').popover();

		$('.pre-loader').fadeOut();
	});

	$(window).on('resize', function(){
		stickyNavbarShrink('fixed');
		$('li.dropdown > a').off('click');
		reponsiveNav('nav-menu');
		heightAdjust();
	});

	/*preloader*/
	// $(window).load(function() {
	// 	$('.pre-loader').fadeOut();
	// });

	function showloading(){
		$('.pre-loader').show();
	}
	function hideloading(){
		$('.pre-loader').fadeOut('slow');
	}
	/*preloader ends*/

	function stickyNavbarShrink(getnav){
		var aboveHeight = 1;
		var winwidth = $(window).width();
		if ($(window).scrollTop() > aboveHeight){
			$('.'+getnav).addClass('shrink');
		}
		else {
			$('.'+getnav).removeClass('shrink');
		}
		/*when scroll*/
		$(window).scroll(function(){
			if ($(window).scrollTop() > aboveHeight){
				$('.'+getnav).addClass('shrink');
			}
			else {
				$('.'+getnav).removeClass('shrink');
			}
		});
	}


	function reponsiveNav(getnav){
		navigationprevent();
		var winwidth = $(window).width();
		if(winwidth > 768){
			$('.'+getnav+' nav .dropdown').on({  
				mouseenter: function(){   
					//$('.dropdown-menu', this).stop( true, true ).slideDown('fast');
					$('.dropdown').removeClass('open');
					$(this).toggleClass('open');
					$('b', this).toggleClass('caret caret-up');                
				},
				mouseleave: function(){
					//$('.dropdown-menu', this).stop( true, true ).hide();
					$(this).removeClass('open'); 
					$('b', this).toggleClass('caret caret-up');       
				}
			});
		}
		else{
			$('.'+getnav+' nav li.dropdown a').on('touchstart', function (event) { /*for the removal of collapse on clicking inside menu, added with removal of data-toggle="dropdown" a.dropdown-toggle */
				$('.'+getnav+' nav .dropdown').removeClass('open');
				$(this).closest('.dropdown').toggleClass('open');
			});

			/*for megamenu*/
			$('.megamenu .head').on('click touchstart', function() {
				$(this).siblings('.head').removeClass('open');
				$(this).toggleClass('open');
			});
		}
	}

	/*prevents click in small device*/
	function navigationprevent(){
		$('a.dropdown-toggle').on('click touch',function(e){
			var winwidth = $(window).width();
			if(winwidth < 768){
				e.preventDefault();
			}
		});
	}


	function heightAdjust(){
		var winwidth = $(window).width();
		var winheight = $(window).height();
		var headerht = $('header.nav-menu').height();
		var textheight = $('.center-txt').height();
		var bodyheight = 0;
		var top = 0;
		if(winheight > 700 || winheight > winwidth){
			bodyheight = winheight;
			$('.full-height').css('min-height', bodyheight+'px');
			top = (bodyheight - textheight)/2;
			$('.full-height .center-txt').css('top', top);
		}
		else{
			bodyheight = 700;
			$('.full-height').css('min-height', bodyheight+'px');
			top = (bodyheight - textheight)/2;
			$('.full-height .center-txt').css('top', top);
		}
	}

	/*equal height*/
	$.fn.extend({
		deasil_equalHeight: function(){
			var heights = $(this).map(function ()
			{
				return $(this).height();
			}).get(),
			maxHeight = Math.max.apply(null, heights);
			$(this).height(maxHeight);
		}
	});


})();