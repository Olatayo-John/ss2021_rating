<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/users.css'); ?>">
<div class="container-fluid">
	<div class="modal addusermodal" style="padding: auto;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header d-flex justify-content-center mb-0">
					<h5 class="text-light font-weight-bolder">Enter user details correctly</h5>
				</div>
				<div class="mb-0 mb-1 alert-danger chkboxerr container" style="display: none;">
					<i class="fas fa-exclamation-circle"></i>
					<strong>Pick at least a method to send login credentials to user!</strong>
				</div>
				<form action="<?php echo base_url('admin/add_user'); ?>" method="post" class="mt-0">
					<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf-token">
					<div class="modal-body">
						<div class="form-group mt-0">
							<label><span class="text-danger font-weight-bolder">* </span>Username:</label>
							<input type="text" name="full_name" class="form-control full_name" placeholder="Pick a Username">
						</div>
						<div class="form-group">
							<label><span class="text-danger font-weight-bolder">* </span>E-mail</label>
							<input type="email" name="email" class="form-control email" placeholder="User E-mail">
							<input type="checkbox" name="mail_chkbox" class="mr-2 ml-2 mail_chkbox"><span class="text-dark font-weight-bolder mail_chkbox_span">Send Login Credentails via E-mail?</span>
						</div>
						<div class="form-group">
							<label><span class="text-danger font-weight-bolder">* </span>Mobile</label>
							<input type="number" name="mobile" class="form-control mobile" placeholder="User Mobile">
							<div class="text-dark text-right font-weight-bolder mobileerr" style="display: none;">Invalid mobile length</div>
							<input type="checkbox" name="mobile_chkbox" class="mr-2 ml-2 mobile_chkbox"><span class="text-dark font-weight-bolder mobile_chkbox_span">Send Login Credentails via SMS?</span>
						</div>
						<div class="form-group">
							<label>Employee ID</label>
							<input type="text" name="eid" class="form-control eid" placeholder="User Employee ID">
						</div>
						<div class="form-group">
							<label>Department</label>
							<select class="form-control dept" name="dept">
								<option value="Staff">Staff</option>
								<option value="Admin">Admin</option>
							</select>
						</div>
						<div class="modalbtngrp justify-content-between d-flex mt-0">
							<button class="btn btn-dark closemodalbtn bradius" type="button">Close</button>
							<button class="btn btn-success adduserbtn bradius" type="submit">Add</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal updateusermodal" style="padding: auto;">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group mt-2">
						<label><span class="text-danger font-weight-bolder">* </span>Username</label>
						<input type="u_fname" name="u_fname" class="form-control u_fname">
					</div>
					<div class="form-group">
						<label><span class="text-danger font-weight-bolder">* </span>E-mail:</label>
						<input type="email" name="u_email" class="form-control u_email">
					</div>
					<div class="form-group">
						<label><span class="text-danger font-weight-bolder">* </span>Mobile</label>
						<input type="number" name="u_mobile" class="form-control u_mobile">
					</div>
					<div class="form-group">
						<label>User Link</label>
						<i class="fas fa-copy" onclick="copyfunc('#u_link')"></i>
						<span class="linkcopyalert text-dark text-right" style="display: none;"><strong>Link copied!</strong></span>
						<input type="text" class="form-control u_link" name="u_link" id="u_link" readonly>
					</div>
					<div class="form-group row">
						<div class="col-md-6">
							<label>Employee ID</label>
							<input type="text" name="u_eid" class="form-control u_eid">
						</div>
						<div class="col-md-6 deptcol">
							<label>Department</label>
							<select class="form-control deptselect" for="u_dept" name="deptselect">
								<option class="u_dept"></option>
								<option class="u_dept_two"></option>
							</select>
						</div>
					</div>
					<div class="form-group mb-0 row">
						<div class="col-md-8">
							<label>Password</label>
							<input type="text" name="u_pwd" class="form-control u_pwd">
						</div>
						<div class="col-md-4">
							<label style="visibility: hidden;" class="gp">Generate Password</label>
							<button class="btn genpwdbtn" type="button" style="background-color: #023E8A;color: white">Generate new password</button>
						</div>
						<div class="text-dark font-weight-bolder pwderr ml-3">Password will be changed on this user</div>
					</div>
				</div>
				<div class="updatebtngrp d-flex justify-content-between mb-2">
					<button class="btn btn-dark closeupdatebtn bradius ml-2">Close</button>
					<input type="hidden" name="ser_id" class="form-control user_id">
					<button class="btn btn-success updatebtn bradius mr-2">Update</button>
				</div>
			</div>
		</div>
	</div>
	<h4 class="text-dark font-weight-bolder text-center userheader">USERS</h4>
	<div class="btnactgrp justify-content-between d-flex container">
		<a href="<?php echo base_url('admin/users_export_csv'); ?>" class="btn csvbtn" style="background: #023E8A;color: #ffff">
			<i class="fas fa-file-csv mr-2"></i>Download CSV
		</a>
		<button class="btn btn-success addnewuserbtn" type="button">
			<i class="fas fa-plus-circle mr-2"></i>Add New User
		</button>
	</div>
	<div class="input-group mb-4 container">
		<input type="text" name="search_user" id="search_user" class="form-control" placeholder="Search by any field" style="border-radius: 0" autofocus>
		<span class="input-group-text" style="border-radius: 0"><i class="fas fa-search text-dark"></i></span>
	</div>
	<div class="table-responsive mb-5 bg-light">
		<table class="table table-bordered table-center table-hover table-light" id="result">
			<tr class="font-weight-bolder thead-dark">
				<th class="text-light">
					<div class="inh">
						<i class="fas fa-arrows-alt-v mr-1" name="full_name" type="desc"></i>
						<span>Username</span>
					</div>
				</th>
				<th class="text-light">
					<div class="inh">
						<i class="fas fa-arrows-alt-v mr-1" name="mobile" type="desc"></i>
						<span>Mobile</span>
					</div>
				</th>
				<th class="text-light">
					<div class="ul">
						<i class="fas fa-arrows-alt-v mr-1" name="form_key" type="desc"></i>
						<span style="white-space: nowrap;">User Link</span>
					</div>
				</th>
				<th class="text-light">
					<div class="inh">
						<i class="fas fa-arrows-alt-v mr-1" name="eid" type="desc"></i>
						<span>Employee ID</span>
					</div>
				</th>
				<th class="text-light">
					<div class="inh">
						<i class="fas fa-arrows-alt-v mr-1" name="dept" type="desc"></i>
						<span>Department</span>
					</div>
				</th>
				<th class="text-danger text-center font-weight-bolder">
					Action
				</th>
			</tr>

			<?php if ($users->num_rows() == '0') : ?>
				<tr class="text-dark">
					<td colspan='7' class='font-weight-bolder text-light text-center'>No data found</td>
				</tr>
			<?php endif; ?>
			<?php if ($users->num_rows() > '0') : ?>
				<?php foreach ($users->result_array() as $info) : ?>
					<tr class="text-dark text-center">
						<td class="font-weight-bolder text-uppercase"><?php echo $info['full_name'] ?></td>
						<td class="font-weight-bolder"><?php echo $info['mobile'] ?></td>
						<td class="font-weight-bolder"><?php echo base_url() . 'user/rate/' . $info['form_key'] ?></td>
						<td class="font-weight-bolder text-uppercase"><?php echo $info['eid'] ?></td>
						<td class="font-weight-bolder text-uppercase"><?php echo $info['dept'] ?></td>
						<td class="font-weight-bolder">
							<div class="d-flex justify-content-between">
								<i class="fas fa-user-edit text-success mr-3" id="<?php echo $info['id'] ?>" style="border: 1px solid green;padding: 5px;border-radius:6px"></i>
								<i class="fas fa-trash-alt text-danger" id="<?php echo $info['id'] ?>" form_key="<?php echo $info['form_key'] ?>" style="border: 1px solid red;padding: 5px;border-radius:6px"></i>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</table><?php echo $links; ?>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo base_url('assets/js/users.js'); ?>"></script>
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
			function load_data(query) {
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();
				$.ajax({
					method: "POST",
					url: "<?php echo base_url('admin/users_search_user') ?>",
					data: {
						query: query,
						[csrfName]: csrfHash
					},
					success: function(data) {
						$('.table').html(data);
					}
				})
			}

			$('#search_user').keyup(function() {
				var search = $(this).val();
				if (search != '') {
					load_data(search);
				} else {
					load_data();
				}
			});

			$(document).on('click', 'i.fa-arrows-alt-v', function() {
				var param = $(this).attr('name');
				var type = $(this).attr('type');
				var csrfName = $('.csrf-token').attr('name');
				var csrfHash = $('.csrf-token').val();
				$.ajax({
					url: "<?php echo base_url('admin/users_filter_param') ?>",
					method: "post",
					data: {
						param: param,
						type: type,
						[csrfName]: csrfHash,
					},
					success: function(data) {
						$('.table').html(data);
					// $('.csrf-token').val(data.token);
					if (type == 'desc') {
						$('.fas').attr('type', 'asc');
					} else {
						$('.fas').attr('type', 'desc');
					}
				},
				error: function(data) {
					alert('Error filtering');
				}
			});
			});

			$(document).on('click', 'i.fa-user-edit', function() {
				var user_id = $(this).attr("id");
				var csrfName = $('.csrf-token').attr('name');
				var csrfHash = $('.csrf-token').val();

				$.ajax({
					url: "<?php echo base_url('admin/get_user') ?>",
					method: "POST",
					data: {
						user_id: user_id,
						[csrfName]: csrfHash
					},
					dataType: "json",
					success: function(data) {
						$('.user_id').val(data.id);
						$('.csrf-token').val(data.token);
						$('.updateusermodal').modal('show');
						$('.u_fname').val(data.u_fname);
						$('.u_email').val(data.u_email);
						$('.u_mobile').val(data.u_mobile);
						$('.u_link').val(data.u_link);
						$('.u_eid').val(data.u_eid);
						$('.u_dept').html(data.u_dept).val(data.u_dept);
						if (data.u_dept == "Admin") {
							$('.u_dept_two').html("Staff").val("Staff");
						} else {
							$('.u_dept_two').html("Admin").val("Admin");
						}
					}
				});
			});

			$(document).on('click', '.updatebtn', function() {
				var user_id = $('.user_id').val();
				var u_fname = $('.u_fname').val();
				var u_email = $('.u_email').val();
				var u_mobile = $('.u_mobile').val();
				var u_link = $('.u_link').val();
				var u_eid = $('.u_eid').val();
				var u_dept = $('.u_dept').val();
				var deptselect = $('.deptselect').val();
				var u_pwd = $('.u_pwd').val();
				var csrfName = $('.csrf-token').attr('name');
				var csrfHash = $('.csrf-token').val();

				if (u_fname == "" || u_fname == null) {
					$('.u_fname').css('border', '2px solid red');
					return false;
				} else {
					$('.u_fname').css('border', '1px solid green');
				}
				if (u_email == "" || u_email == null) {
					$('.u_email').css('border', '2px solid red');
					return false;
				} else {
					$('.u_email').css('border', '1px solid green');
				}
				if (u_mobile == "" || u_mobile == null) {
					$('.mobile').css('border', '2px solid red');
					return false;
				}
				if (u_mobile.length < 10 || u_mobile.length > 10) {
					$('.u_mobileerr').show();
					return false;
				} else {
					$('.u_mobile').css('border', '1px solid green');
					$('.u_mobileerr').hide();
				}

				$.ajax({
					url: "<?php echo base_url('admin/update_user') ?>",
					method: "POST",
					data: {
						user_id: user_id,
						u_fname: u_fname,
						u_email: u_email,
						u_mobile: u_mobile,
						u_link: u_link,
						u_eid: u_eid,
						deptselect: deptselect,
						u_pwd: u_pwd,
						[csrfName]: csrfHash
					},
					beforeSend: function() {
						$('.updatebtn').removeClass("btn-success").addClass("btn-danger");
						$('.updatebtn').html("Updating...");
						$('.updatebtn').attr("disabled", "disabled");
						$('.updatebtn').css("cursor", "not-allowed");
					},
					error: function(data) {
						alert("Error updating");
					}
				})
				.done(function() {
					window.location.reload();
				});
			});

			$(document).on('click', 'i.fa-trash-alt', function() {
				var user_id = $(this).attr('id');
				var form_key = $(this).attr('form_key');
				var csrfHash = $('.csrf-token').val();
				var csrfName = $('.csrf-token').attr('name');
				var con = confirm("Are you sure you want to delete this user and all of its data?");
				if (con == false) {
					return false;
				} else if (con == true) {
					$.ajax({
						url: "<?php echo base_url('admin/delete_user'); ?>",
						method: "post",
						data: {
							user_id: user_id,
							form_key: form_key,
							[csrfName]: csrfHash
						},
						error: function(data) {
							alert('Failed to delete. Please refresh page');
						}
					})
					.done(function() {
						window.location.reload();
					});
				}
			});

		});
	</script>