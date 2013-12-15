(function($) {
	$('[required=required]').change()
	
	$('[required=required]').change(function() {
	  $(this).removeClass('error-highlight');
	});
$.fn.validation = function() {		

    var error = 0;
		var focusfield="";
	$('[required=required]', this).each(function() {
		var input = $(this).val();
		if (input == "") {
			if(focusfield=="") focusfield=$(this);
			$('span.error-message', this).remove();
			$(this).append('<span class="error-message"></span>');
			$('span.error-message', this).html('This field cannot be empty');
			$(this).addClass("error-highlight");
			error++;
		} else {
			$('span.error-message', this).remove();
			$(this).removeClass("error-highlight");
		}
	});
	$('.required >select', this).each(function() {
		var input = $(this).val();
		if (input == "") {
			if(focusfield=="") focusfield=$(this);
			$('span.error-message', this).remove();
			$(this).append('<span class="error-message"></span>');
			$('span.error-message', this).html('This field cannot be empty');
			$(this).addClass("error-highlight");
			error++;
		} else {
			$('span.error-message', this).remove();
			$(this).removeClass("error-highlight");
		}
	});
	
	if (error == 0) {
		return true;
	} else {
		$(focusfield).focus();
		return false;
	}
};

})(jQuery);

$(document).ready(function() {
	$("#myform").submit(function () {
		return $(this).validation();
	});
});