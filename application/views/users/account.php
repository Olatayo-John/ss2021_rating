<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/quota.css'); ?>">
<div class="modal emailsmsusermodal">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="mb-2 modal-header">
				<a href="<?php echo base_url('admin/emailsms_export_csv'); ?>" class="btn emailsms_export_csv col-md-3" style="background: #023E8A;color: #ffff">
					<i class="fas fa-file-csv mr-2"></i>Export as CSV
				</a>
			</div>
			<div class="modal-body" style="height:400px;overflow:scroll;">
				<table class="table table-bordered table-center table-hover tableuserreview table-sm" id="tableuserreview">
					<tr class="font-weight-bolder" style="background-color: #1B5E20">
						<th class="text-light"><span class="icon">
								Sent To
							</span></th>
						<th class="text-light"><span>
								Body
							</span class="icon"></th>
						<th class="text-light"><span>
								Date
							</span></th>
					</tr>
					<?php foreach ($sent_links as $row) : ?>
						<tr>
							<?php if (!$row->mobile) : ?>
								<td><?php echo $row->email ?></td>
							<?php endif; ?>
							<?php if (!$row->email) : ?>
								<td><?php echo $row->mobile ?></td>
							<?php endif; ?>
							<td><?php echo $row->body ?></td>
							<td class="text-danger font-weight-bolder"><?php echo $row->sent_at ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			</div>
			<div class="updatebtngrp text-right mb-2">
				<button class="btn btn-dark closeemailsmsbtn bradius mr-3">Close</button>
			</div>
		</div>
	</div>
</div>

<?php if ($this->session->userdata('rr_admin') == "0") : ?>
	<h4 class="text-center text-dark font-weight-bolder mb-3">LINKS SENT</h4>
	<div class="container row mb-5">
		<div class="col-md-6 total_link text-dark">
			<h4 class="text-center stared">SMS sent</h4>
			<div class="value tsms"><?php echo $user[0]['sms'] ?></div>
		</div>
		<div class="col-md-6 total_link">
			<h4 class="text-center stared">Emails sent</h4>
			<div class="value temail"><?php echo $user[0]['email'] ?></div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->session->userdata('rr_admin') == "0") : ?>
	<h4 class="text-center text-dark font-weight-bolder mb-3">YOUR OVERRALL RATINGS</h4>
	<div class="container-fluid row mb-5">
		<div class="col-md-2 total_link text-secondary">
			<h4 class="text-center text-secondary">Total Ratings</h4>
			<div class="value text-secondary"><?php echo $user[0]['total_links'] ?></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-success">5 stared</h4>
			<div class="value"><?php echo $user[0]['5_star'] ?></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-info">4 stared</h4>
			<div class="value"><?php echo $user[0]['4_star'] ?></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-warning">3 stared</h4>
			<div class="value"><?php echo $user[0]['3_star'] ?></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared">2 stared</h4>
			<div class="value"><?php echo $user[0]['2_star'] ?></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-danger">1 stared</h4>
			<div class="value"><?php echo $user[0]['1_star'] ?></div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->session->userdata('rr_admin') == "1") : ?>
	<h4 class="text-center text-dark font-weight-bolder mb-3 mt-4">QUOTA POINTS</h4>
	<div class="container-fluid row mb-5">
		<div class="col-md-4 total_link">
			<h4 class="text-center stared">BOUGHT</h4>
			<div class="value text-success"><?php echo number_format($balance->bought) ?></div>
		</div>
		<div class="col-md-4 total_link">
			<h4 class="text-center stared text-secondary">USED POINTS</h4>
			<div class="value text-secondary"><?php echo number_format($balance->used) ?></div>
		</div>
		<div class="col-md-4 total_link">
			<h4 class="text-center stared">BALANCE</h4>
			<div class="value text-danger"><?php echo number_format($balance->bal) ?></div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->session->userdata('rr_admin') == "1") : ?>
	<h4 class="text-center text-dark font-weight-bolder mb-3">LINKS SENT</h4>
	<div class="container row mb-5">
		<div class="col-md-4 total_link">
			<h4 class="text-center stared text-secondary">Total</h4>
			<div class="value temailsms text-secondary"><?php echo $all_sms[0]->sms + $all_email[0]->email ?></div>
			<a href="" style="text-decoration: none;" class="emailsmsmodalbtn">View all</a>
		</div>
		<div class="col-md-4 total_link text-dark">
			<h4 class="text-center stared">SMS sent</h4>
			<div class="value tsms"><?php echo $all_sms[0]->sms ?></div>
		</div>
		<div class="col-md-4 total_link">
			<h4 class="text-center stared">Emails sent</h4>
			<div class="value temail"><?php echo $all_email[0]->email ?></div>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->session->userdata('rr_admin') == "1") : ?>
	<h4 class="text-center text-dark font-weight-bolder mb-3">OVERRALL RATINGS</h4>
	<div class="container-fluid row mb-5">
		<div class="col-md-2 total_link text-secondary">
			<h4 class="text-center text-secondary">Total Ratings</h4>
			<div class="avalue tr text-secondary"></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-success">5 stared</h4>
			<div class="avalue atl5"></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-info">4 stared</h4>
			<div class="avalue atl4"></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-warning">3 stared</h4>
			<div class="avalue atl3"></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared">2 stared</h4>
			<div class="avalue atl2"></div>
		</div>
		<div class="col-md-2 total_link">
			<h4 class="text-center stared text-danger">1 stared</h4>
			<div class="avalue atl1"></div>
		</div>
	</div>
<?php endif; ?>

<div class="container yourlink mb-5">
	<label class="font-weight-bolder text-dark mb-0 text-right">YOUR LINK</label>
	<div class="linkcopyalert text-success" style="display: none;"><strong>Link copied!</strong>
	</div>
	<input type="text" name="user_link" value="<?php echo base_url() . "user/rate/" . $user[0]['form_key'] ?>" class="form-control user_link" readonly id="user_link">
	<button class="btn text-light copylinkbtn mt-2" onclick="copyfunc('#user_link')" style="border-radius: 0px;background-color: #0B3954;color: white"><i class="fas fa-copy"></i> Copy link
	</button>
</div>

<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>" class="csrf_token">

<script type="text/javascript" src="<?php echo base_url('assets/js/quota.js'); ?>"></script>
<script type="text/javascript">
	function copyfunc(element) {
		var link = $("<input>");
		$("body").append(link);
		link.val($(element).val()).select();
		document.execCommand("copy");
		link.remove();
		$('.linkcopyalert').show();
	}

	$(document).ready(function() {
		var csrfName = $('.csrf_token').attr('name');
		var csrfHash = $('.csrf_token').val();
		$.ajax({
			url: "<?php echo base_url('user/bar_data'); ?>",
			method: "post",
			dataType: "json",
			data: {
				[csrfName]: csrfHash,
			},
			success: function(data) {
				$('.tr').html(data.tl);
				$('.atl5').html(data.tl5);
				$('.atl4').html(data.tl4);
				$('.atl3').html(data.tl3);
				$('.atl2').html(data.tl2);
				$('.atl1').html(data.tl1);
				$('.csrf_token').val(data.token);
			},
			error: function(data) {
				alert('Error showing');
			}
		});



	});
</script>