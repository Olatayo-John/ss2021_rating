<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/register.css'); ?>">
<style type="text/css">
	label{
		margin-bottom: 0;
	}
</style>
<div class="container col-md-6" id="content">
	<form action="<?php echo base_url('user/register'); ?>" method="post">
		<h4 class="text-center mt-3 mb-3 text-dark">REGISTRATION</h4>
		<input type="hidden" class="csrf_token" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		<div class="form-group">
			<label><span class="text-danger font-weight-bolder">* </span>Username:</label>
			<input type="text" name="full_name" class="form-control full_name" autofocus placeholder="Pick a Username">
		</div>
		<div class="form-group">
			<label><span class="text-danger font-weight-bolder">* </span>E-mail:</label>
			<input type="email" name="email" class="form-control email" placeholder="Your E-mail">
		</div>
		<div class="form-group">
			<label><span class="text-danger font-weight-bolder">* </span>Mobile Number:</label>
			<input type="number" name="mobile" class="form-control mobile" placeholder="Your Mobile">
			<div class="text-danger font-weight-bolder mobileerr" style="display: none;">Invalid mobile length</div>
		</div>
		<div class="form-group">
			<label>Employee ID:</label>
			<input type="text" name="eid" class="form-control eid" placeholder="Your Employee ID">
		</div>
		<div class="form-group">
			<label>Department:</label>
			<input type="text" name="dept" class="form-control dept" value="Staff" readonly>
		</div>
		
		<div class="btngrp container">
			<button class="btn text-light registerbtn" type="submit" style="background-color: #0B3954;">Register</button>
			<a href="<?php echo base_url('user/login'); ?>" class="loginbtn">
			Already a user? <i class="far fa-arrow-alt-circle-right"></i></a>
		</div>
	</form>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/register.js'); ?>"></script>