<?php
defined('BASEPATH') or exit('No direct script access allowed');
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");
require_once(APPPATH . "libraries/paytm/config_paytm.php");
require_once(APPPATH . "libraries/paytm/encdec_paytm.php");

class Admin extends CI_Controller
{
	public function index()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
			exit;
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "1") {
			$this->votes();
		}
	}

	public function users($offset = 0)
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$config['base_url'] = base_url() . "admin/users/";
		$config['total_rows'] = $this->db->count_all('users');
		$config['per_page'] = 10;
		$config["uri_segment"] = 3;
		$config['attributes'] = array('class' => 'page-link');
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#" class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();

		$data['users'] = $this->Adminmodel->get_user_details($config["per_page"], $offset);
		$this->load->view('templates/header');
		$this->load->view('admin/users', $data);
		$this->load->view('templates/footer');
	}

	public function votes($offset = 0)
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$config['base_url'] = base_url() . "admin/votes/";
		$config['total_rows'] = $this->db->count_all('user_details');
		$config['per_page'] = 10;
		$config["uri_segment"] = 3;
		$config['attributes'] = array('class' => 'page-link');
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#" class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		$this->pagination->initialize($config);
		$data['links'] = $this->pagination->create_links();

		$data['details'] = $this->Adminmodel->get_user_votes($config["per_page"], $offset);
		$this->load->view('templates/header');
		$this->load->view('admin/votes', $data);
		$this->load->view('templates/footer');
	}

	public function users_export_csv()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=users.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('ID', 'Username', 'E-mail', 'Mobile', 'User Link', 'Employee ID', 'Department'));
		$data = $this->Adminmodel->users_export_csv();
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	public function votes_export_csv()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=votes.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('ID', 'Username', 'SMS', 'Email', 'Votes', '5 Star', '4 Star', '3 Star', '2 Star', '1 Star'));
		$data = $this->Adminmodel->votes_export_csv();
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	public function indiv_votes_export_csv($key)
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=individual_votes.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('ID', 'Name', 'Message', 'Star', 'Mobile', 'IP', 'Date'));
		$data = $this->Adminmodel->indiv_votes_export_csv($key);
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	public function search_ind_votes()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$output = '';
		$query = '';
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
		}
		$data = $this->Adminmodel->search_ind_votes($query, $_POST['key']);
		$output .= '<table class="table table-bordered table-center table-hover tableuserreview"  id="tableuserreview">
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
		</tr>';
		if ($data->num_rows() == 0) {
			$output .= '<tr class="text-dark truserreview">
			<td colspan="6" class="font-weight-bolder text-dark text-center">No data found</td>
			</tr>';
		} else {
			foreach ($data->result_array() as $info) {
				$output .= '<tr class="text-dark text-center truserreview">
				<td class="font-weight-bolder">' . $info["name"] . '</td>
				<td class="font-weight-bolder">' . $info["review_msg"] . '</td>
				<td class="font-weight-bolder">' . $info["star"] . '</td>
				<td class="font-weight-bolder">' . $info["mobile"] . '</td>
				<td class="font-weight-bolder">' . $info["user_ip"] . '</td>
				<td class="font-weight-bolder text-danger">' . $info["rated_at"] . '</td>
				</tr>';
			}
		}
		echo $output;
	}

	public function add_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|html_escape');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|html_escape');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|html_escape');
		$this->form_validation->set_rules('eid', 'Employee ID', 'trim|html_escape');
		$this->form_validation->set_rules('dept', 'Department', 'trim|html_escape');

		if ($this->form_validation->run() === FALSE) {
			$data['details'] = $this->Adminmodel->get_user_details();
			$this->load->view('templates/header');
			$this->load->view('admin/index', $data);
			$this->load->view('templates/footer');
		} else {
			$form_key =  mt_rand(0, 10000);
			$randpwd =  mt_rand(0, 10000000);
			// $form_key = md5($rand);
			$pwd = password_hash($randpwd, PASSWORD_DEFAULT);
			$res = $this->Usermodel->register($form_key, $pwd);
			if ($res !== TRUE) {
				$this->session->set_flashdata('reg_failed', 'Registration Failed');
				redirect('user/register');
			} else {
				if (isset($_POST['mail_chkbox']) && isset($_POST['mobile_chkbox'])) {
					$fname = $this->input->post('full_name');
					$email = $this->input->post('email');
					$link = base_url() . "user/rate/" . $form_key;
					$mobile = $this->input->post('mobile');
					$login_link = base_url();
					$res = $this->send_email_code($fname, $randpwd, $email, $link, $login_link);

					$body = "Hello " . $fname . "\n\nBelow are your login credentails:\nUsername: " . $fname . "\nPassword: " . $randpwd . "\nLink: " . $link . "\nShare the above link to get reviews.\nYou can login here " . $login_link . "\n\nIf you have any questions, send us an email at info@nktech.in.\n\nBest Regards,\nNKTECH\nhttps://nktech.in";
					$url = "http://onextelbulksms.in/shn/api/pushsms.php?usr=621665&key=010BrbJ20v1c2eCc8LGih6RlTIGqKN&sndr=KARUNJ&ph=+91" . $mobile . "&text=";
					$req = curl_init();
					$complete_url = $url . curl_escape($req, $body) . "&rpt=1";
					curl_setopt($req, CURLOPT_URL, $complete_url);
					$result = curl_exec($req);

					if ($result === false) {
						$this->session->set_flashdata('sms_link_send_err', 'Error sending SMS');
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						$this->session->set_flashdata('adduser_emailndmobile_code', 'User added. Login credentials sent to user e-mail and mobile');
						redirect($_SERVER['HTTP_REFERER']);
					}
					curl_close($req);
				} elseif (isset($_POST['mail_chkbox'])) {
					$fname = $this->input->post('full_name');
					$email = $this->input->post('email');
					$link = base_url() . "user/rate/" . $form_key;
					$mobile = $this->input->post('mobile');
					$login_link = base_url();
					$res = $this->send_email_code($fname, $randpwd, $email, $link, $login_link);
					$this->session->set_flashdata('adduser_email_code', 'User added. Login credentials sent to user e-mail');
					redirect($_SERVER['HTTP_REFERER']);
				} elseif (isset($_POST['mobile_chkbox'])) {
					$fname = $this->input->post('full_name');
					$email = $this->input->post('email');
					$link = base_url() . "user/rate/" . $form_key;
					$mobile = $this->input->post('mobile');
					$login_link = base_url();
					$body = "Hello " . $fname . "\n\nBelow are your login credentails:\nUsername: " . $fname . "\nPassword: " . $randpwd . "\nLink: " . $link . "\nShare the above link to get reviews.\nYou can login here " . $login_link . "\n\nIf you have any questions, send us an email at info@nktech.in.\n\nBest Regards,\nNKTECH\nhttps://nktech.in";

					$url = "http://onextelbulksms.in/shn/api/pushsms.php?usr=621665&key=010BrbJ20v1c2eCc8LGih6RlTIGqKN&sndr=KARUNJ&ph=+91" . $mobile . "&text=";
					$req = curl_init();
					$complete_url = $url . curl_escape($req, $body) . "&rpt=1";
					curl_setopt($req, CURLOPT_URL, $complete_url);
					$result = curl_exec($req);

					if ($result === false) {
						$this->session->set_flashdata('sms_link_send_err', 'Error sending SMS');
						redirect($_SERVER['HTTP_REFERER']);
					} else {
						$this->session->set_flashdata('adduser_mobile_code', 'User added. Login credentials sent to user mobile');
						redirect($_SERVER['HTTP_REFERER']);
					}
					curl_close($req);
				}
			}
		}
	}

	public function send_email_code($fname, $randpwd, $email, $link, $login_link)
	{
		$config['protocol']    = 'smtp';
		$config['smtp_host']    = 'ssl://smtp.gmail.com';
		$config['smtp_port']    = '465';
		$config['smtp_timeout'] = '7';
		$config['smtp_user']    = 'jvweedtest@gmail.com';
		$config['smtp_pass']    = 'Jvweedtest9!';
		$config['charset']    = 'iso-8859-1';
		$config['mailtype'] = 'text';
		$config['validation'] = TRUE;

		$this->load->library('email', $config);
		$this->email->set_newline("\r\n");

		$data = array(
			'fname' => $fname,
			'randpwd' => $randpwd,
			'link' => $link,
			'login_link' => $login_link,
		);
		$body = "Hello " . $fname . "\n\nBelow are your login credentails:\nUsername: " . $fname . "\nPassword: " . $randpwd . "\nLink: " . $link . "\nShare the above link to get reviews.\nYou can login here " . $login_link . "\n\nIf you have any questions, send us an email at info@nktech.in.\n\nBest Regards,\nNKTECH\nhttps://nktech.in";

		$this->email->from('jvweedtest@gmail.com', 'Rating');
		$this->email->to($email);
		$this->email->subject("Login Credentials");
		$this->email->message($body);

		$this->email->send();
	}

	public function votes_filter_param()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$data = $this->Adminmodel->votes_filter_param($_POST['param'], $_POST['type']);
		$output = "";
		$output .= '<table class="table table-bordered table-center table-hover" id="result">
		<tr class="font-weight-bolder thead-dark">
		<th><div class="inh">
		<i class="fas fa-arrows-alt-v" name="name" type="desc"></i>
		<span>Username</span>
		</div></th>
		<th><div class="inh">
		<i class="fas fa-arrows-alt-v" name="sms" type="desc"></i>
		<span>SMS</span>
		</div class="icon"></th>
		<th><div class="inh">
		<i class="fas fa-arrows-alt-v" name="email" type="desc"></i>
		<span>Email</span>
		</div class="icon"></th>
		<th><div class="inh">
		<i class="fas fa-arrows-alt-v" name="total_links" type="desc"></i>
		<span>Votes</span>
		</div class="icon"></th>
		<th><div class="inh">				
		<i class="fas fa-arrows-alt-v" name="5_star" type="desc"></i>
		<span>5 Star</span>
		</div></th>
		<th><div class="inh">				
		<i class="fas fa-arrows-alt-v" name="4_star" type="desc"></i>
		<span>4 Star</span>
		</div></th>
		<th><div class="inh">			
		<i class="fas fa-arrows-alt-v" name="3_star" type="desc"></i>
		<span>3 Star</span>
		</div></th>
		<th><div class="inh">				
		<i class="fas fa-arrows-alt-v" name="2_star" type="desc"></i>
		<span>2 Star</span>
		</div></th>
		<th><div class="inh">					
		<i class="fas fa-arrows-alt-v" name="1_star" type="desc"></i>
		<span>1 Star</span>
		</div></th>
		<th class="text-light text-center font-weight-bolder">
		View Votes
		</th>
		</tr>';
		if ($data->num_rows() == 0) {
			$output .= '<tr class="text-dark">
			<td colspan="10" class="font-weight-bolder text-dark text-center">No data found</td>
			</tr>';
		} else {
			foreach ($data->result_array() as $info) {
				$output .= '<tr class="text-dark text-center">
				<td class="font-weight-bolder">' . $info["name"] . '</td>
				<td class="font-weight-bolder">' . $info["sms"] . '</td>
				<td class="font-weight-bolder">' . $info["email"] . '</td>
				<td class="font-weight-bolder">' . $info["total_links"] . '</td>
				<td class="font-weight-bolder">' . $info["5_star"] . '</td>
				<td class="font-weight-bolder">' . $info["4_star"] . '</td>
				<td class="font-weight-bolder">' . $info["3_star"] . '</td>
				<td class="font-weight-bolder">' . $info["2_star"] . '</td>
				<td class="font-weight-bolder">' . $info["1_star"] . '</td>
				<td class="font-weight-bolder">
				<i class="fas fa-poll text-danger"  form_key="' . $info['form_key'] . '"></i>
				</td>
				</tr>';
			}
		}
		echo $output;
	}

	public function votes_search_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$output = '';
		$query = '';
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
		}
		$data = $this->Adminmodel->votes_search_user($query);
		$output .= '<table class="table table-bordered table-center table-hover" id="result">
		<tr class="font-weight-bolder thead-dark">
		<th><div class="inh">
		<i class="fas fa-arrows-alt-v" name="name" type="desc"></i>
		<span>Username</span>
		</div></th>
		<th><div class="inh">
		<i class="fas fa-arrows-alt-v" name="name" type="desc"></i>
		<span>SMS</span>
		</div class="icon"></th>
		<th><div class="inh">
		<span>Email</span>
		</div class="icon"></th>
		<th><div class="inh">
		<span>Votes</span>
		</div class="icon"></th>
		<th><div class="inh">				
		<span>5 Star</span>
		</div></th>
		<th><div class="inh">				
		<span>4 Star</span>
		</div></th>
		<th><div class="inh">			
		<span>3 Star</span>
		</div></th>
		<th><div class="inh">				
		<span>2 Star</span>
		</div></th>
		<th><div class="inh">					
		<span>1 Star</span>
		</div></th>
		<th class="text-light text-center font-weight-bolder">
		View Votes
		</th>
		</tr>';
		if ($data->num_rows() == 0) {
			$output .= '<tr class="text-dark">
			<td colspan="10" class="font-weight-bolder text-dark text-center">No data found</td>
			</tr>';
		} else {
			foreach ($data->result_array() as $info) {
				$output .= '<tr class="text-dark text-center">
				<td class="font-weight-bolder">' . $info["name"] . '</td>
				<td class="font-weight-bolder">' . $info["sms"] . '</td>
				<td class="font-weight-bolder">' . $info["email"] . '</td>
				<td class="font-weight-bolder">' . $info["total_links"] . '</td>
				<td class="font-weight-bolder">' . $info["5_star"] . '</td>
				<td class="font-weight-bolder">' . $info["4_star"] . '</td>
				<td class="font-weight-bolder">' . $info["3_star"] . '</td>
				<td class="font-weight-bolder">' . $info["2_star"] . '</td>
				<td class="font-weight-bolder">' . $info["1_star"] . '</td>
				<td class="font-weight-bolder">
				<i class="fas fa-poll text-danger"  form_key="' . $info['form_key'] . '"></i>
				</td>
				</tr>';
			}
		}
		echo $output;
	}

	public function users_filter_param()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$data = $this->Adminmodel->users_filter_param($_POST['param'], $_POST['type']);
		$output = "";
		$output .= '<table class="table table-bordered table-center table-hover table-light" id="result">
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
		</tr>';
		if ($data->num_rows() == 0) {
			$output .= '<tr class="text-dark">
			<td colspan="7" class="font-weight-bolder text-light text-center">No data found</td>
			</tr>';
		} else {
			foreach ($data->result_array() as $info) {
				$output .= '<tr class="text-dark text-center">
				<td class="font-weight-bolder text-uppercase">' . $info['full_name'] . '</td>
				<td class="font-weight-bolder">' . $info['mobile'] . '</td>
				<td class="font-weight-bolder">' . base_url() . 'user/rate/' . $info['form_key'] . '</td>
				<td class="font-weight-bolder text-uppercase">' . $info['eid'] . '</td>
				<td class="font-weight-bolder text-uppercase">' . $info['dept'] . '</td>
				<td class="font-weight-bolder">
				<div class="d-flex justify-content-between">
				<i class="fas fa-user-edit text-success mr-3" id="' . $info['id'] . '" style="border: 1px solid green;padding: 5px;border-radius:6px"></i>
				<i class="fas fa-trash-alt text-danger" id="' . $info['id'] . '" style="border: 1px solid red;padding: 5px;border-radius:6px"></i>
				</div>
				</td>
				</tr>';
			}
		}
		echo $output;
	}

	public function users_search_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$output = '';
		$query = '';
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
		}
		$data = $this->Adminmodel->users_search_user($query);
		$output .= '<table class="table table-bordered table-center table-hover table-light" id="result">
		<tr class="font-weight-bolder thead-dark">
		<th class="text-light">
		<div class="inh">
		<span>Username</span>
		</div>
		</th>
		<th class="text-light">
		<div class="inh">
		<span>Mobile</span>
		</div>
		</th>
		<th class="text-light">
		<div class="ul">
		<span style="white-space: nowrap;">User Link</span>
		</div>
		</th>
		<th class="text-light">
		<div class="inh">
		<span>Employee ID</span>
		</div>
		</th>
		<th class="text-light">
		<div class="inh">
		<span>Department</span>
		</div>
		</th>
		<th class="text-danger text-center font-weight-bolder">
		Action
		</th>
		</tr>';
		if ($data->num_rows() == 0) {
			$output .= '<tr class="text-dark">
			<td colspan="7" class="font-weight-bolder text-dark text-center">No data found</td>
			</tr>';
		} else {
			foreach ($data->result_array() as $info) {
				$output .= '<tr class="text-dark text-center">
				<td class="font-weight-bolder text-uppercase">' . $info['full_name'] . '</td>
				<td class="font-weight-bolder">' . $info['mobile'] . '</td>
				<td class="font-weight-bolder">' . base_url() . 'user/rate/' . $info['form_key'] . '</td>
				<td class="font-weight-bolder text-uppercase">' . $info['eid'] . '</td>
				<td class="font-weight-bolder text-uppercase">' . $info['dept'] . '</td>
				<td class="font-weight-bolder">
				<div class="d-flex justify-content-between">
				<i class="fas fa-user-edit text-success mr-3" id="' . $info['id'] . '" style="border: 1px solid green;padding: 5px;border-radius:6px"></i>
				<i class="fas fa-trash-alt text-danger" id="' . $info['id'] . '" style="border: 1px solid red;padding: 5px;border-radius:6px"></i>
				</div>
				</td>
				</tr>';
			}
		}
		echo $output;
	}

	public function get_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect($_SERVER['HTTP_REFERER']);
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect($_SERVER['HTTP_REFERER']);
		} else {
			$output = array();
			$data = $this->Adminmodel->get_user($_POST['user_id']);
			foreach ($data as $row) {
				$output['id'] = $row->id;
				$output['u_fname'] = $row->full_name;
				$output['u_email'] = $row->email;
				$output['u_mobile'] = $row->mobile;
				$output['u_link'] =  base_url() . "user/rate/" . $row->form_key;
				$output['form_key'] =  $row->form_key;
				$output['u_eid'] = $row->eid;
				$output['u_dept'] = $row->dept;
				$output['token'] = $this->security->get_csrf_hash();
			}
			echo json_encode($output);
		}
	}

	public function votes_get_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		} else {
			$data['users'] = $this->Adminmodel->get_ratings($_POST['key']);
			$data['token'] = $this->security->get_csrf_hash();
			echo json_encode($data);
		}
	}

	public function update_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		} else {
			$data = $this->Adminmodel->update_user($_POST['user_id']);
			$this->session->set_flashdata('user_updated', 'User details updated');
		}
	}

	public function delete_user()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		} else {
			$res = $this->Adminmodel->delete_user($_POST['user_id'], $_POST['form_key']);
			$this->session->set_flashdata('user_deleted', 'User deleted');
		}
	}

	public function pick_plan()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			//redirect('user/login');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$this->load->view('templates/header');
		$this->load->view('admin/pick_plan');
		$this->load->view('templates/footer');
	}

	public function save_plan()
	{
		$checkSum = "";
		$data = array();

		$data["MID"] = PAYTM_MERCHANT_MID;
		$data["CUST_ID"] = $this->session->userdata('rr_id');
		$data["ORDER_ID"] = mt_rand(0, 10000000);
		$data["INDUSTRY_TYPE_ID"] = "Retail";
		$data["CHANNEL_ID"] = "WEB";
		$data["TXN_AMOUNT"] = $this->input->post('plan_amount');
		$data["WEBSITE"] = PAYTM_MERCHANT_WEBSITE;
		$data["CALLBACK_URL"] = base_url("admin/pgResponses");

		$checkSum = getChecksumFromArray($data, PAYTM_MERCHANT_KEY);
		$this->load->view('templates/header');
		$this->load->view('admin/pgresponse', ['paytm_info' => $data, 'checkSum' => $checkSum]);
		$this->load->view('templates/footer');
	}

	public function pgResponses()
	{
		$paytmChecksum = "";
		$paramList = array();
		$isValidChecksum = "FALSE";
		$paramList = $_POST;
		$user_id = $this->session->userdata('rr_id');
		$form_key = $this->session->userdata('rr_form_key');

		$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : "";
		$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum);
		$userData = array(
			'm_id' => $_POST['MID'],
			'txn_id' => "",
			'order_id' => $_POST['ORDERID'],
			'currency' => $_POST['CURRENCY'],
			'paid_amt' => $_POST['TXNAMOUNT'],
			'payment_mode' => "",
			'gateway_name' => "",
			'bank_txn_id' => "",
			'bank_name' => "",
			'status' => $_POST['STATUS'],
		);
		if ($isValidChecksum == "TRUE") {
			if ($_POST["STATUS"] == "TXN_SUCCESS") {
				if (isset($_POST) && count($_POST) > 0) {
					if ($_POST['TXNAMOUNT'] == "25000") {
						$quota_amount = "25000";
					} elseif ($_POST['TXNAMOUNT'] == "50000") {
						$quota_amount = "75000";
					} elseif ($_POST['TXNAMOUNT'] == "100000") {
						$quota_amount = "200000";
					} elseif ($_POST['TXNAMOUNT'] == "500000") {
						$quota_amount = "1250000";
					}
					$userData = array(
						'quota_bought' => $quota_amount,
						'm_id' => $_POST['MID'],
						'txn_id' => $_POST['TXNID'],
						'order_id' => $_POST['ORDERID'],
						'currency' => $_POST['CURRENCY'],
						'paid_amt' => $_POST['TXNAMOUNT'],
						'payment_mode' => $_POST['PAYMENTMODE'],
						'gateway_name' => $_POST['GATEWAYNAME'],
						'bank_txn_id' => $_POST['BANKTXNID'],
						'bank_name' => $_POST['BANKNAME'],
						'check_sum_hash' => $_POST['CHECKSUMHASH'],
						'status' => $_POST['STATUS'],
					);
					$res = $this->Adminmodel->save_payment($userData);
					// $res = true;
					if ($res == true) {
						$this->session->set_flashdata('reg_succ', 'Payment Done.');
						//$this->payment_status($userData);
						$this->load->view('templates/header');
						$this->load->view('admin/pay_status', ['userData' => $userData]);
						$this->load->view('templates/footer');
					} else {
						$this->session->set_flashdata('payment_save_err', 'Error saving contacts to DATABASE.');
						$this->load->view('templates/header');
						$this->load->view('admin/pay_status', ['userData' => $userData]);
						$this->load->view('templates/footer');
					}
				} else {
					$this->session->set_flashdata('sub_failed', 'Payment Failed.');
					$this->load->view('templates/header');
					$this->load->view('admin/pay_status', ['userData' => $userData]);
					$this->load->view('templates/footer');
				}
			} else {
				$this->session->set_flashdata('sub_failed', 'Payment Failed.');
				$this->load->view('templates/header');
				$this->load->view('admin/pay_status', ['userData' => $userData]);
				$this->load->view('templates/footer');
			}
		} else {
			// $this->logout();
			$this->session->set_flashdata('sub_failed', 'Payment Failed.');
			$this->load->view('templates/header');
			$this->load->view('admin/pay_status', ['userData' => $userData]);
			$this->load->view('templates/footer');
		}
	}

	public function emailsms_export_csv()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=sent_links.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('ID', 'Mobile', 'E-mail', 'Subject', 'Body', 'Link For', 'Sent at'));
		$data = $this->Adminmodel->emailsms_export_csv();
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	public function feedbacks()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user/login');
		}
		if ($this->session->userdata('rr_admin') == "0") {
			$this->session->set_flashdata('acces_denied', 'Access Denied.');
			redirect('user/login');
		}
		$data['r_reviews'] = $this->Adminmodel->r_feedback_reviews();
		$data['q_reviews'] = $this->Adminmodel->q_feedback_reviews();
		$this->load->view('templates/header');
		$this->load->view('admin/feedbacks',$data);
		$this->load->view('templates/footer');
	}

	public function logout()
	{
		$this->session->unset_userdata('rr_id');
		$this->session->unset_userdata('rr_admin');
		$this->session->unset_userdata('rr_fname');
		$this->session->unset_userdata('rr_email');
		$this->session->unset_userdata('rr_mobile');
		$this->session->unset_userdata('rr_form_key');
		$this->session->unset_userdata('rr_logged_in');
		//$this->session->sess_destroy();

		$this->session->set_flashdata('log_out', 'Logged out');
		redirect('user');
	}
}
