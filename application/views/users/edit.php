<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/edit.css'); ?>">
<div class="container col-md-6 mt-3" id="content">
	<form action="<?php echo base_url('user/edit'); ?>" method="post" class="editform">

		<input type="hidden" class="csrf_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		<div class="form-group">
			<label>Full Name:</label>
			<input type="text" name="full_name" class="form-control full_name" value="<?php echo $info->full_name ?>">
		</div>
		<div class="form-group">
			<label>E-mail:</label>
			<input type="email" name="email" class="form-control email" value="<?php echo $info->email ?>">
		</div>
		<div class="form-group">
			<div class="row">
				<div class="col-md-6">
					<label>Mobile Number:</label>
					<input type="number" name="mobile" class="form-control mobile" value="<?php echo $info->mobile ?>">
				</div>
				<div class="col-md-6 eidcol">
					<label>Employee ID:</label>
					<input type="text" name="eid" class="form-control eid" value="<?php echo $info->eid ?>">
				</div>
			</div>
		</div>
		<div class="form-group">
			<div class="d-flex justify-content-between">
				<label class="mb-0">Your Link:</label>
				<span class="linkcopyalert text-danger text-right" style="display: none;"><strong>Link copied!</strong></span>
			</div>
			<input type="text" name="form_key" id="form_key" class="form-control form_key" value="<?php echo base_url() . "user/rate/" . $info->form_key ?>" readonly>
			<button class="btn btn-xs copylinkbtn mt-2" onclick="copyfunc('#form_key')" type="button" style="border-radius: 0px;background-color: #0B3954;color: white"><i class="fas fa-copy"></i> Copy link
			</button>
		</div>
		<div class="form-group">
			<label>Department:</label>
			<input type="text" name="dept" class="form-control dept" value="<?php echo $info->dept ?>" readonly>
		</div>

		<div class="btngrp">
			<button class="btn text-light updatebtn col-md-4" style="background-color: #0B3954;" type="submit">Update</button>
		</div>
	</form>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/edit.js'); ?>"></script>
<script type="text/javascript">
	function copyfunc(element) {
		var link = $("<input>");
		$("body").append(link);
		link.val($(element).val()).select();
		document.execCommand("copy");
		link.remove();
		$('.linkcopyalert').show();
	}
</script>