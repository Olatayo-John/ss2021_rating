$(document).ready(function() {
	$('button.registerbtn').click(function() {
		// e.preventDefault();
		var full_name= $('.full_name').val();		
		var mobile= $('.mobile').val();			

		if (full_name == "" || full_name == null) {
			$('.full_name').css('border','2px solid red');
			return false;
		}else{
			$('.full_name').css('border','0 solid red');
		}
		if (mobile == "" || mobile == null) {
			$('.mobile').css('border','2px solid red');
			return false;
		}if (mobile.length < 10 || mobile.length > 10) {
			$('.mobileerr').show();
			return false;
		}
		else{
			$('.mobile').css('border','0 solid red');
			$('.mobileerr').hide();
		}
		$.ajax({
			success: function() {
				$('.registerbtn').attr('disabled','disabled');
				$('.registerbtn').html('Processing...');
				$('.registerbtn').css('cursor','not-allowed');
				$('.registerbtn').removeClass('btn-info').addClass('btn-danger');
			}
		});
	});
});