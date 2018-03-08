jQuery(document).ready(
    function($) {

		$("html").niceScroll({
			cursorwidth: "14px",
			scrollspeed: 70,
			background: "#fff",
			autohidemode: true,
			zindex:9999999
		});

		var question = $('body').hasClass('single-dwqa-question');
		if(question)
		{
			$('.current_page_parent').removeClass('active')
									 .next().addClass('active');
		}
    }
);

jQuery(function($) {

	$(window).on('scroll', function(){
		if( $(window).scrollTop()>50 ){
			$('#header').addClass('navbar-fixed');
		} else {
			$('#header').removeClass('navbar-fixed');
		}
	});

	//#main-slider
	var staticWidth = $(window).width();

	$( ".separator1-top, .separator3-bottom, .separator4-top, .separator5-bottom" ).css('border-left-width', staticWidth+'px');
	$( ".separator1-bottom, .separator2-top" ).css('border-right-width', staticWidth+'px');

	if ( staticWidth < 768 ){
		$('.separator1-top, .separator3-bottom, .separator4-top').css('border-bottom-width','50px');
		$('.separator1-bottom, .separator2-top, .separator3-bottom, .separator5-bottom').css('border-top-width','50px');	
	}
	else
	{
		$('.separator1-top, .separator3-bottom, .separator4-top').css('border-bottom-width','140px');
		$('.separator1-bottom, .separator2-top, .separator3-bottom, .separator5-bottom').css('border-top-width','140px');
	}

	$(function(){
		$('#main-slider.carousel').carousel({
			interval: 8000
		});
	});

	$(function(){
		$('#carousel-example-generic').carousel({
			interval: false
		});
	});

	$( window ).resize(function() {

		var width = $(window).width();

		$.doTimeout( 'resize', 100, function(){
			$( ".separator1-top, .separator3-bottom, .separator4-top, .separator5-bottom" ).css('border-left-width', width+'px');
			$( ".separator1-bottom, .separator2-top, .separator3-bottom" ).css('border-right-width', width+'px');
		});

		if ( width < 768 ){
			$('.separator1-top, .separator3-bottom, .separator4-top').css('border-bottom-width','50px');
			$('.separator1-bottom, .separator2-top, .separator3-bottom, .separator5-bottom').css('border-top-width','50px');	
		}
		else
		{
			$('.separator1-top, .separator3-bottom, .separator4-top').css('border-bottom-width','140px');
			$('.separator1-bottom, .separator2-top, .separator3-bottom, .separator5-bottom').css('border-top-width','140px');
		}
	});


	//Vertically middle

	$(window).resize(function(){
		$( '.centered' ).each(function( e ) {
			$(this).css('margin-top',  ($(this).closest('.row').height() - $(this).height())/2);
		});
	});

	$(window).load(function(){
		$( '.centered' ).each(function( e ) {
			$(this).css('margin-top',  ($(this).closest('.row').height() - $(this).height())/2);
		});
	});


	//contact form
	var form = $('.contact-form');

	form.submit(function () {
		$this = $(this);

		form_data = $this.serialize();

		//console.log(data);

		$.post($(this).attr('action'), form_data, function(data) {

			if( data.type=='success' ){
				form.slideUp('slow');
			}

			$('#contact-alert').removeClass('alert-danger alert-success');
			$('#contact-alert').addClass('alert-'+data.type).text(data.message).fadeIn();//.delay(3000).fadeOut();
		});
		return false;
	});

	//goto top
	$('.gototop').click(function(event) {
		event.preventDefault();
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 500);
	});

	$('.circle-nav a').on('click',function(event){
		event.preventDefault();
		
		var link = this.href;
		var parts = link.split('#');
		var target = parts[1];
		var target_offset = $("#"+target).offset();
		var target_top = target_offset.top;
		$('html, body').animate({scrollTop:target_top}, 400);
	})

	//Pretty Photo
	$("a[rel^='prettyPhoto']").prettyPhoto({
		social_tools: false
	});

	var $social_tools = $('#menu-shop li').first();

	$social_tools.on('click',function(){
		var $toggole = $(this).find('ul');
		$toggole.slideToggle(600);
	})


});