jQuery(document).ready(function($) {	
    $('*:first-child').addClass('first-child');
    $('*:last-child').addClass('last-child');
    $('*:nth-child(even)').addClass('even');
    $('*:nth-child(odd)').addClass('odd');


	$('li.menu-item.icon').each(function(){
		var cont = $(this);
		var list = $(this).attr('class').split(/\s+/);
		var link = $(this).find('a');
		console.log(list);
		$.each(list, function(index, item) {
			console.log(item);
			if (item.match(/fa-(.*)/)) {
				//do something
				link.addClass('fa');
				link.find('span').addClass('screen-reader-text');
				link.addClass(item);
				cont.removeClass(item);
			}
		});
	});

    $('.section.overlay').wrapInner('<div class="overlay"></div>');


    var numwidgets = $('#genesis-footer-widgets section.widget').length;
    var widget_cols = (12/numwidgets);
    $('#genesis-footer-widgets section.widget').addClass('col-md-' + widget_cols);

	$.each(['show', 'hide'], function (i, ev) {
        var el = $.fn[ev];
        $.fn[ev] = function () {
          this.trigger(ev);
          return el.apply(this, arguments);
        };
      });

	$('.nav-footer ul.menu>li').after(function(){
		if(!$(this).hasClass('last-child') && $(this).hasClass('menu-item') && $(this).css('display')!='none'){
			return '<li class="separator">|</li>';
		}
	});
	
	$('.section.expandable .expand').click(function(){
	    var target = $(this).parents('.section-body').find('.content');
	    console.log(target);
	    if(target.hasClass('open')){
            target.removeClass('open');
            $(this).html('MORE <i class="fa fa-angle-down"></i>');
	    } else {
	        target.addClass('open');
	        $(this).html('LESS <i class="fa fa-angle-up"></i>');
	    }
	});

	$('.genesis-teaser').matchHeight();

	$('.login-trigger').click(function(e){
		e.preventDefault();
        $('#login-modal').modal('toggle');
    });

	$('#_fields_customer_0_confirm-email').blur(function() {
        email = $(this).parents('p.form-field').prev('p.form-field').find('input[type=email]');
        if ($(this).val() != email.val()) {
            alert('Email address does not match. Please check!');
        }
    });

});