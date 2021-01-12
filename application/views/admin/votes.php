<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/votes.css'); ?>">
<input type="hidden" name="<?php echo $this->security->get_csrf_token_name() ?>" value="<?php echo $this->security->get_csrf_hash() ?>" class="csrf_token">
<h4 class="text-dark font-weight-bolder text-center userheader">VOTES</h4>
<div class="container row mb-2">
	<div class="modal updateusermodal">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="mb-2 d-flex flex-row modal-header">
					<a href="" class="btn indiv_votes_export_csv col-md-3" style="background: #023E8A;color: #ffff">
						<i class="fas fa-file-csv mr-2"></i>Export as CSV
					</a>
					<input type="hidden" name="input_form_key" class="input_form_key">
					<input type="text" name="search_ind_votes" id="search_ind_votes" class="form-control ml-5" placeholder="Search by Name" style="border-radius: 0" autofocus>
				</div>
				<div class="modal-body" style="height:400px;overflow:scroll;">
					<table class="table table-bordered table-center table-hover tableuserreview" id="tableuserreview">
						<tr class="font-weight-bolder" style="background-color: #1B5E20">
							<th class="text-light"><span class="icon">
								Name
							</span></th>
							<th class="text-light"><span>
								Message
							</span class="icon"></th>
							<th class="text-light"><span>
								Star
							</span></th>
							<th class="text-light"><span>
								Mobile
							</span class="icon"></th>
							<th class="text-light"><span>
								IP
							</span></th>
							<th class="text-light"><span>
								Date
							</span></th>
						</tr>
					</table>
				</div>
				<div class="updatebtngrp text-right mb-2">
					<button class="btn btn-dark closeupdatebtn bradius mr-3">Close</button>
					<input type="hidden" name="ser_id" class="form-control user_id">
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-2 total_link text-secondary">
		<h4 class="text-center stared">Votes</h4>
		<div class="value tl text-secondary"></div>
	</div>
	<div class="col-md-2 total_link">
		<h4 class="text-center stared text-success">5 stared</h4>
		<div class="value tl5"></div>
	</div>
	<div class="col-md-2 total_link">
		<h4 class="text-center stared text-info">4 stared</h4>
		<div class="value tl4"></div>
	</div>
	<div class="col-md-2 total_link">
		<h4 class="text-center stared text-warning">3 stared</h4>
		<div class="value tl3"></div>
	</div>
	<div class="col-md-2 total_link">
		<h4 class="text-center stared text-dark">2 stared</h4>
		<div class="value tl2"></div>
	</div>
	<div class="col-md-2 total_link">
		<h4 class="text-center stared text-danger">1 stared</h4>
		<div class="value tl1"></div>
	</div>
</div>
<div class="container-fluid">
	<div class="btnactgrp container text-right d-flex justify-content-between">
		<select class="form-control tablelimit" style="width: 80px;visibility: hidden;">
			<option value="2">2</option>
			<option value="5">5</option>
			<option value="10">10</option>
			<option value="20">20</option>
			<option value="All">All</option>
		</select>
		<a href="<?php echo base_url('admin/votes_export_csv'); ?>" class="btn btnexport" style="background: #023E8A;color: #ffff">
			<i class="fas fa-file-csv mr-2"></i>Export as CSV
		</a>
	</div>
	<div class="input-group mb-4 container">
		<input type="text" name="search_user" id="search_user" class="form-control" placeholder="Search by Name" style="border-radius: 0;" autofocus>
		<span class="input-group-text text-dark" style="border-radius: 0"><i class="fas fa-search text-dark"></i></span>
	</div>
	<div class="table-responsive mb-5 bg-light">
		<table class="table table-bordered table-center table-hover table-light" id="result">
			<tr class="font-weight-bolder thead-dark">
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="name" type="desc"></i>
						<span>Username</span>
					</div>
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="sms" type="desc"></i>
						<span>SMS</span>
					</div class="icon">
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="email" type="desc"></i>
						<span>Email</span>
					</div class="icon">
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="total_links" type="desc"></i>
						<span>Votes</span>
					</div class="icon">
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="5_star" type="desc"></i>
						<span>5 Star</span>
					</div>
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="4_star" type="desc"></i>
						<span>4 Star</span>
					</div>
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="3_star" type="desc"></i>
						<span>3 Star</span>
					</div>
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="2_star" type="desc"></i>
						<span>2 Star</span>
					</div>
				</th>
				<th>
					<div class="inh">
						<i class="fas fa-arrows-alt-v" name="1_star" type="desc"></i>
						<span>1 Star</span>
					</div>
				</th>
				<th class="text-light text-center font-weight-bolder">
					View Votes
				</th>
			</tr>

			<?php if ($details->num_rows() == '0') : ?>
				<tr class="text-dark">
					<td colspan='8' class='font-weight-bolder text-light text-center'>No data found</td>
				</tr>
			<?php endif; ?>
			<?php if ($details->num_rows() > '0') : ?>
				<?php foreach ($details->result_array() as $info) : ?>
					<tr class="text-dark text-center">
						<td class="font-weight-bolder"><?php echo $info['name'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['sms'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['email'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['total_links'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['5_star'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['4_star'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['3_star'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['2_star'] ?></td>
						<td class="font-weight-bolder tv"><?php echo $info['1_star'] ?></td>
						<td class="font-weight-bolder">
							<i class="fas fa-poll text-danger" form_key="<?php echo $info['form_key'] ?>"></i>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			</table><?php echo $links; ?>
		</div>
	</div>

	<script type="text/javascript" src="<?php echo base_url('assets/js/votes.js'); ?>"></script>
	<script type="text/javascript">
		$(document).ready(function() {

			function load_data(query) {
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();
				$.ajax({
					method: "POST",
					url: "<?php echo base_url('admin/votes_search_user') ?>",
					data: {
						query: query,
						[csrfName]: csrfHash
					},
					success: function(data) {
						$('table#result').html(data);
					// $('i.fa-arrows-alt-v').hide();
				}
			})
			}

			$('#search_user').keyup(function() {
				var search = $(this).val();
				if (search != '') {
					load_data(search);
				} else {
					load_data();
				// $('i.fa-arrows-alt-v').show();
			}
		});

			function search_ind_votes_load_data(query) {
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();
				var key = $('.input_form_key').val();
				$.ajax({
					method: "POST",
					url: "<?php echo base_url('admin/search_ind_votes') ?>",
					data: {
						query: query,
						key: key,
						[csrfName]: csrfHash
					},
					success: function(data) {
						$('table#tableuserreview').html(data);
					}
				})
			}

			$('#search_ind_votes').keyup(function() {
				var indvotes_search = $(this).val();
				if (indvotes_search != '') {
					search_ind_votes_load_data(indvotes_search);
				} else {
					search_ind_votes_load_data();
				}
			});

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
					$('.tl').html(data.tl);
					$('.tl5').html(data.tl5);
					$('.tl4').html(data.tl4);
					$('.tl3').html(data.tl3);
					$('.tl2').html(data.tl2);
					$('.tl1').html(data.tl1);
					$('.csrf_token').val(data.token);
				},
				error: function(data) {
					alert('Error showing');
				}
			});

			$(document).on('click', 'i.fa-arrows-alt-v', function() {
				var param = $(this).attr('name');
				var type = $(this).attr('type');
				var csrfName = $('.csrf-token').attr('name');
				var csrfHash = $('.csrf-token').val();
				$.ajax({
					url: "<?php echo base_url('admin/votes_filter_param') ?>",
					method: "post",
					data: {
						param: param,
						type: type,
						[csrfName]: csrfHash,
					},
					success: function(data) {
						$('table#result').html(data);
					// $('.csrf-token').val(data.token);
					if (type == 'desc') {
						$('.fas').attr('type', 'asc');
					} else {
						$('.fas').attr('type', 'desc');
					}
				},
				error: function(data) {
					alert('Refresh the page');
				}
			});
			});

			$(document).on('click', 'i.fa-poll', function() {
				var key = $(this).attr("form_key");
				var csrfName = $('.csrf_token').attr('name');
				var csrfHash = $('.csrf_token').val();

				$.ajax({
					url: "<?php echo base_url('admin/votes_get_user') ?>",
					method: "POST",
					data: {
						key: key,
						[csrfName]: csrfHash
					},
					dataType: "json",
					success: function(data) {
						$('.csrf_token').val(data.token);
						$('.input_form_key').val(key);
						$(".indiv_votes_export_csv").attr("href", "indiv_votes_export_csv/" + key);
						if (data['users'].length == 0) {
							var table = $('table.tableuserreview');
							var tr = $('<tr class="truserreview"></tr>');
							tr.append('<td colspan="6" class="font-weight-bolder text-dark text-center">User has no data</td>');
							table.append(tr);
							$('.updateusermodal').modal('show');
						}
						var table = $('table.tableuserreview');
						for (var i = 0; i < data['users'].length; i++) {
							var tr = $('<tr class="truserreview"></tr>');
							tr.append('<td class="font-weight-bolder text-dark">' + data['users'][i].name + '</td>');
							tr.append('<td class="font-weight-bolder">' + data['users'][i].review_msg + '</td>');
							tr.append('<td class="font-weight-bolder">' + data['users'][i].star + '</td>');
							tr.append('<td class="font-weight-bolder">' + data['users'][i].mobile + '</td>');
							tr.append('<td class="font-weight-bolder">' + data['users'][i].user_ip + '</td>');
							tr.append('<td class="text-danger font-weight-bolder">' + data['users'][i].rated_at + '</td>');
							table.append(tr);
							$('.updateusermodal').modal('show');
						}
					}
				});
			});

			$('.tv').each(function() {
				$(this).prop('Counter', 0).animate({
					Counter: $(this).text()
				}, {
					duration: 1000,
					easing: 'swing',
					step: function(now) {
						$(this).text(Math.ceil(now));
					}
				});
			});

		});
	</script>