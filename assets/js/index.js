$(document).ready(function() {
	$('button.sndassmsbtn').click(function() {
		$('.sms_gen_link_form').show();
		$('.gen_link_form').hide();
	});

	$('button.sndasmailbtn').click(function() {
		$('.sms_gen_link_form').hide();
		$('.gen_link_form').show();
	});

	$('button.importemail').click(function() {
		var sel_conn= $('#email_select').attr('conn');
		if (sel_conn == "true") {
			var ans= confirm("Are you sure you want to import a new data? Your imported data will be cleared.");
			if (ans == true) {
				$('.email_options').remove();
				$('.emailmodal').show();
			}else{
				return false;
			}
		}else{
			$('.emailmodal').show();
		}
	});

	$('button.closebtn').click(function() {
		$('.emailmodal').hide();
	});

	$('.singlemailsend').click(function() {
		$('.email_options').remove();
		$('#email_select').attr('conn','false');
		$('#email_select').hide();
		$('#email').val('');
		$('#email').show();
		bseu= "<?php echo base_url('user/send_link'); ?>";
		$("#gen_link_form").attr('action', bseu);
	});

	$('button.smsimport').click(function() {
		var sel_conn= $('#sms_select').attr('conn');
		if (sel_conn == "true") {
			var ans= confirm("Are you sure you want to import a new data? Your imported data will be cleared.");
			if (ans == true) {
				$('.sms_options').remove();
				$('.smsmodal').show();
				$('#sms_select').attr('conn','false');
			}else{
				return false;
			}
		}else{
			$('.smsmodal').show();
		}
	});

	$('button.smsclosebtn').click(function() {
		$('.smsmodal').hide();
	});

	$('.singlsmssend').click(function() {
		$('.sms_options').remove();
		$('#sms_select').attr('conn','false');
		$('#sms_select').hide();
		$('#mobile').val('');
		$('#mobile').show();
		bseu= "<?php echo base_url('user/sms_send_link'); ?>";
		$("#sms_gen_link_form").attr('action', bseu);
	});

});