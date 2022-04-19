(function() {
	"use strict";
	$(document).ready(function(){
		controlpanel();

		var primary_color = '#558B2F';
		var base_color = '#111111';
		var head_font = 'Raleway';
		var body_font = 'Open Sans';


		$('#primary-color').colpick({
			layout:'hex',
			submit:1,
			color: primary_color,
			colorScheme:'dark',
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				$(el).css('border-color','#'+hex);
				$(el).val('#'+hex);
			},
			onSubmit: function () {
				$('.colpick').hide();
			},
		}).keyup(function(){
			$(this).colpickSetColor(this.value);
		}).css('border-color', primary_color);

		$('#base-color').colorpicker({
			displayIndicator: false,
			hideButton: true,
			history: false,
			color: base_color,
			customTheme: [ '#111111', '#2a2a2a','#3F4042','#365768','#2D3A4B','#49264B','#385E98','#C0C4D7','#ebebeb']
		});
		$("#base-color").on("change.color", function(event, color){
			$("#base-color").css('border-color', color);
		});



		$('#hexchange').on('click',function(){
			showloading();
			var primarycolor = $('#primary-color').val();
			var basecolor = $('#base-color').val();
			var head = $('#headfont').val();
			var body = $('#bodyfont').val();

			Cookies.set('primarycolor', primarycolor, { expires: 1 });
			Cookies.set('basecolor', basecolor, { expires: 1 });
			Cookies.set('headfont', head, { expires: 1 });
			Cookies.set('bodyfont', body, { expires: 1 });

			var headimport = head.split(' ').join('+');
			var bodyimport = body.split(' ').join('+');

			$.ajax({
				type: "POST",
				url: "rendercss.php",
				data: { 
					'primary_color': primarycolor, 
					'base_color': basecolor, 
					'head_font': head, 
					'body_font': body, 
					'head_import': headimport, 
					'body_import': bodyimport
				},
				cache: false,
				success: function(data)
				{	
					$('#moldcustomize').html(data).promise().done(function(){
						hideloading();
					});
					
				}
			});
		});


		$('#hexrefresh').on('click', function(){
			showloading();
			$('#maincss').attr('href', 'assets/css/main.css');
			Cookies.remove('primarycolor', null);
			Cookies.remove('basecolor', null);
			Cookies.remove('headfont', null);
			Cookies.remove('bodyfont', null);

			$('#primary-color').val(primary_color).css('border-color', primary_color);
			$('#base-color').val(base_color).css('border-color', base_color);
			$('#headfont').val(head_font);
			$('#bodyfont').val(body_font);

			$.ajax({
 				type: "GET", // use "GET for server
 				url: "rendercss.php",
 				data: { 
 					'primary_color': primary_color, 
 					'base_color': base_color, 
 					'head_font': head_font, 
 					'body_font': body_font
 				},
 				cache: false,
 				success: function(data)
 				{
 					$('#moldcustomize').html(data).promise().done(function(){
 						hideloading();
 					});
 				}
 			});
		});



		/*if cookie is set load html with cookie values*/
		if((Cookies.get('primarycolor') != null) || (Cookies.get('basecolor') != null) || (Cookies.get('headfont') != null) || (Cookies.get('bodyfont') != null)){

			var primarycolor = Cookies.get('primarycolor');
			var basecolor = Cookies.get('basecolor');
			var headcookie = Cookies.get('headfont');
			var bodycookie = Cookies.get('bodyfont');
			$('#primary-color').val(primarycolor).css('border-color', primarycolor);
			$('#base-color').val(basecolor).css('border-color', basecolor);
			$('#headfont').val(headcookie);
			$('#bodyfont').val(bodycookie);
			if((primarycolor == '#4d5886') || (primarycolor == '#7c8c3c') || (primarycolor == '#8c6c26') || (primarycolor == '#e75c61')){
				if (primarycolor == '#4d5886') {
					$('#maincss').attr('href', 'assets/css/main4d5886.css');
				}
				else if (primarycolor == '#7c8c3c') {
					$('#maincss').attr('href', 'assets/css/main7c8c3c.css');
				}
				else if (primarycolor == '#8c6c26') {
					$('#maincss').attr('href', 'assets/css/main8c6c26.css');
				}
				else if(primarycolor == '#e75c61'){
					$('#maincss').attr('href', 'assets/css/maine75c61.css');
				}
			}
			else{
				$.ajax({
					type: "POST",
					async: false,
					url: "rendercss.php",
					data: { 
						'primary_color': primarycolor, 
						'base_color': basecolor, 
						'head_font': headcookie, 
						'body_font': bodycookie
					},
					cache: false,
					success: function(data)
					{	
						$('#moldcustomize').html(data);
					}
				});
			}

		}

		function controlpanel(){
			$('.panel-opener').on('click',function(){
				$('.control-panel').toggleClass('show');
			});
			if($(window).width() > 768){
				$('.panel-opener').helpcursor({
					position: 'left',
					color: '#fff',
					msg: 'You can customize Template from here.'
				});

				/*hide on scroll down*/
				$(window).on('scroll', function(){
					$('.help').delay(2000).fadeOut('slow');
				});
			}
		}
	});

$.fn.helpcursor = function(options) {
	var defaults = $.extend({
		position : 'left',
		color: '',
		msg : 'Help Cursor text'
	}, options );

	return this.each(function() {
		var html;
		if(defaults.color !== null){
			html = '<div class="help '+defaults.position+'" style="color:'+defaults.color+'">';
			html += defaults.msg;
			html += '<span class="icon-handdrawn-arrow-'+defaults.position+'"></span>';
			html += '</div>';
		}
		else{
			html = '<div class="help '+defaults.position+'">';
			html += defaults.msg;
			html += '<span class="icon-handdrawn-arrow-'+defaults.position+'"></span>';
			html += '</div>';
		}
		$(this).addClass('helpcursor').append(html);
	});
};

function showloading(){
	$('.pre-loader').show();
}
function hideloading(){
	$('.pre-loader').fadeOut('slow');
}


/*Autocomplete*/
var availableTags = ["Open Sans",
"Open Sans",
"Roboto",
"Slabo 27px",
"Lato",
"Oswald",
"Source Sans Pro",
"Montserrat",
"Raleway",
"PT Sans",
"Roboto Slab",
"Droid Sans",
"Lora",
"Ubuntu",
"Merriweather",
"Droid Serif",
"Playfair Display",
"Titillium Web",
"Noto Sans",
"PT Serif",
"Bitter",
"Oxygen",
"Fjalla One",
"Noto Serif",
"Lobster",
"Bree Serif",
"Anton",
"Libre Baskerville",
"Pacifico",
"Abril Fatface",
"Vollkorn",
"Dancing Script",
"Kaushan Script",
"Playball"
];


$("#headfont, #bodyfont").autocomplete({
	source: availableTags});

})();