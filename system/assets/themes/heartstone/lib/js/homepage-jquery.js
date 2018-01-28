jQuery(document).ready(function($) {
	var numwidgets = $('.footer-widgets-1 div.widget').length;
	$('.footer-widgets-1').addClass('cols-'+numwidgets);
	//$('.footer-widgets-1 .widget,.footer-widgets-1 .widget .widget-wrap').equalHeightColumns();
});