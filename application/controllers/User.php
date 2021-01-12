<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
	public function index()
	{
		if ($this->session->userdata('rr_admin') == "0") {
			redirect('user/account');
		}
		if ($this->session->userdata('rr_admin') == "1") {
			redirect('admin/votes');
		}
		$this->load->view('templates/header');
		$this->load->view('users/login');
		$this->load->view('templates/footer');
	}

	public function login()
	{
		if ($this->session->userdata('rr_logged_in')) {
			redirect('user/rating');
		}
		$this->form_validation->set_rules('uname', 'Username', 'required|trim|html_escape');
		$this->form_validation->set_rules('pwd', 'Password', 'required|trim|html_escape');

		if ($this->form_validation->run() === FALSE) {
			$this->index();
		} else {
			$validate = $this->Usermodel->login();
			if ($validate == FALSE) {
				$this->session->set_flashdata('invalid_login', 'Username/Password is wrong');
				redirect('user');
				exit();
			}
			if ($validate) {
				$rr_id = $validate->id;
				$rr_admin = $validate->admin;
				$rr_s_admin = $validate->s_admin;
				$rr_fname = $validate->full_name;
				$rr_email = $validate->email;
				$rr_mobile = $validate->mobile;
				$rr_form_key = $validate->form_key;

				$rr_user_sess = array(
					'rr_id' => $rr_id,
					'rr_admin' => $rr_admin,
					'rr_s_admin' => $rr_s_admin,
					'rr_fname' => $rr_fname,
					'rr_email' => $rr_email,
					'rr_mobile' => $rr_mobile,
					'rr_form_key' => $rr_form_key,
					'rr_sub' => '0',
					'rr_logged_in' => TRUE,
				);
				$this->session->set_userdata($rr_user_sess);
				if ($this->session->userdata('rr_admin') == "0") {
					$this->session->set_flashdata('valid_login', 'Welcome ' . $this->session->userdata('rr_fname') . "!");
					redirect('user/account');
				}
				if ($this->session->userdata('rr_admin') == "1") {
					$admin_sub = $this->Usermodel->get_admin_sub();
					$this->session->set_userdata('rr_sub', $admin_sub->sub);
					$this->session->set_flashdata('valid_login', 'Welcome ' . $this->session->userdata('rr_fname') . "!");
					redirect('admin/votes');
				}
			}
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('rr_id');
		$this->session->unset_userdata('rr_admin');
		$this->session->unset_userdata('rr_s_admin');
		$this->session->unset_userdata('rr_fname');
		$this->session->unset_userdata('rr_email');
		$this->session->unset_userdata('rr_mobile');
		$this->session->unset_userdata('rr_form_key');
		$this->session->unset_userdata('rr_sub');
		$this->session->unset_userdata('rr_logged_in');
		//$this->session->sess_destroy();

		$this->session->set_flashdata('log_out', 'Logged out');
		redirect('user');
	}

	public function register()
	{
		if ($this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('logout_first', 'Log out first.');
			redirect($_SERVER['HTTP_REFERER']);
		}
		$this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|html_escape');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|html_escape');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|html_escape');
		$this->form_validation->set_rules('eid', 'Employee ID', 'trim|html_escape');
		$this->form_validation->set_rules('dept', 'Department', 'trim|html_escape');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header');
			$this->load->view('users/register');
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
				exit();
			} else {
				$fname = htmlentities($this->input->post('full_name'));
				$email = htmlentities($this->input->post('email'));
				$mobile = htmlentities($this->input->post('mobile'));
				$link = base_url() . "user/rate/" . $form_key;
				$login_link = base_url();
				$res = $this->send_email_code($fname, $randpwd, $email, $link, $login_link);

				$body = "Hello " . $fname . "\n\nBelow are your login credentails:\nUsername: " . $fname . "\nPassword: " . $randpwd . "\nLink: " . $link . "\nShare the above link to get reviews.\nYou can login here " . $login_link . "\n\nIf you have any questions, send us an email at info@nktech.in.\n\nBest Regards,\nNKTECH\nhttps://nktech.in";

				$url = "http://onextelbulksms.in/shn/api/pushsms.php?usr=621665&key=010BrbJ20v1c2eCc8LGih6RlTIGqKN&sndr=KARUNJ&ph=+91" . $mobile . "&text=";

				$req = curl_init();
				$complete_url = $url . curl_escape($req, $body) . "&rpt=1";
				curl_setopt($req, CURLOPT_URL, $complete_url);
				$result = curl_exec($req);

				$this->session->set_flashdata('email_code', 'User credentials sent to e-mail and mobile. Login with it');
				redirect('user/login');
				exit();
			}
		}
	}

	public function edit()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('login_first', 'Login first.');
			redirect('user/login');
		}
		$this->form_validation->set_rules('full_name', 'Full Name', 'required|trim|html_escape');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|html_escape');
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|html_escape');
		$this->form_validation->set_rules('eid', 'Employee ID', 'trim|html_escape');
		$this->form_validation->set_rules('dept', 'Department', 'trim|html_escape');

		if ($this->form_validation->run() === FALSE) {
			$data['info'] = $this->Usermodel->get_info();
			$this->load->view('templates/header');
			$this->load->view('users/edit', $data);
			$this->load->view('templates/footer');
		} else {
			$res = $this->Usermodel->edit();
			if ($res !== TRUE) {
				$this->session->set_flashdata('update_failed', 'Update Failed');
				redirect('user/edit');
				exit();
			} else {
				$this->session->set_flashdata('update_succ', 'Profile Updated');
				redirect('user/edit');
				exit();
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
			'mobile' => $mobile,
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

	public function rating()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('login_first', 'Login first.');
			redirect('user/login');
		} else {
			$this->load->view('templates/header');
			$this->load->view('users/index');
			$this->load->view('templates/footer');
		}
	}

	public function get_link()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user');
		}
		$cq_res = $this->Adminmodel->check_quota_expire();
		if ($cq_res !== false) {
			$db_email = $cq_res->email;
			if ($this->session->userdata('rr_admin') == "0") {
				$this->session->set_flashdata('quota_expired', 'Quota has expired. Request has been sent to admin for renewal');
				$this->quota_send_mail_expire($db_email);
			} else if ($this->session->userdata('rr_admin') == "1") {
				$this->session->set_flashdata('quota_expired', 'Quota has expired');
			}
		} else {
			$res = $this->Usermodel->get_link($_POST['id']);
			if (!$res) {
				return FALSE;
				exit();
			}
			if ($res) {
				$output[] = array();
				$output['id'] = $res->id;
				$output['full_name'] = $res->full_name;
				$output['mobile'] = $res->mobile;
				$output['email'] = $res->email;
				$output['eid'] = $res->eid;
				$output['dept'] = $res->dept;
				$output['form_key'] = $res->form_key;
				$output['token'] = $this->security->get_csrf_hash();
				echo json_encode($output);

				$myfile = fopen("body.txt", "w") or die("Unable to open file!");
				$txt = "Please vote for your city to become No.1\n";
				fwrite($myfile, $txt);
				$txt = "Below is the link to vote\n";
				fwrite($myfile, $txt);
				// $txt = base_url() . "user/rate/" . $output['form_key'] . "\n\n";
				$txt = base_url() . "user/rp/" . $output['form_key'] . "\n\n";
				fwrite($myfile, $txt);
				$txt = "Best Regards\n";
				fwrite($myfile, $txt);
				$txt = "Nagar Nigam Ghaziabad";
				fwrite($myfile, $txt);
				fclose($myfile);
			}
		}
	}

	public function quota_send_mail_expire($db_email)
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

		$body = "Hello.\n\nThis email is to inform you that your Quota has expired.SMS, Emails and Future ratings woun't be recorded\nClick here to login to your account to renew for a new plan " . base_url('admin/pick_plan') . "\nIf you have any questions, send us an email at info@nktech.in.\n\nBest Regards,\nNKTECH\nhttps://nktech.in";

		$this->email->from('jvweedtest@gmail.com', 'Rating');
		$this->email->to($db_email);
		$this->email->subject('Quota Limit');
		$this->email->message($body);

		if ($this->email->send()) {
			return true;
		} else {
			return $this->email->print_debugger();
		}
	}

	public function send_link()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user');
		}
		$this->form_validation->set_rules('email', 'E-mail', 'required|trim|valid_email|html_escape');
		$this->form_validation->set_rules('subj', 'Subject', 'required|trim|html_escape');
		$this->form_validation->set_rules('bdy', 'Body', 'required|trim|html_escape');

		if ($this->form_validation->run() == FALSE) {
			$this->rating();
		} else {
			$cq_res = $this->Adminmodel->check_quota_expire();
			if ($cq_res !== false) {
				$db_email = $cq_res->email;
				if ($this->session->userdata('rr_admin') == "0") {
					$this->quota_send_mail_expire($db_email);
					$this->session->set_flashdata('quota_expired', 'Quota has expired. Request has been sent to admin for renewal');
					redirect('user/account');
				} else if ($this->session->userdata('rr_admin') == "1") {
					$this->session->set_flashdata('quota_expired', 'Quota has expired');
					redirect('admin/pick_plan');
				}
			} else {
				$email = $this->input->post('email');
				$subj = $this->input->post('subj');
				$bdy = $this->input->post('bdy');
				$mail_res = $this->link_send_mail($email, $subj, $bdy);
				if ($mail_res !== true) {
					$this->session->set_flashdata('link_send_err', $mail_res);
					redirect('user/rating');
					exit();
				} else {
					$res = $this->Usermodel->save_info();
					if ($res !== true) {
						$this->session->set_flashdata('link_send_err', 'Error saving contacts to DATABASE.');
						redirect('user/rating');
						exit();
					} else {
						$length = '1';
						$cq_res = $this->Adminmodel->quota_update($length);
						if ($cq_res !== false) {
							$db_email = $cq_res->email;
							$this->quota_send_mail_expire($db_email);
							$this->session->set_flashdata('link_send_succ', 'Link sent successfully');
							redirect('user/rating');
						} else {
							$this->session->set_flashdata('link_send_succ', 'Link sent successfully');
							redirect('user/rating');
						}
					}
				}
			}
		}
	}

	public function link_send_mail($email, $subj, $bdy)
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


		$this->email->from('jvweedtest@gmail.com', 'Rating');
		$this->email->to($email);
		$this->email->subject($subj);
		$this->email->message($bdy);

		if ($this->email->send()) {
			return true;
		} else {
			return $this->email->print_debugger();
		}
	}

	public function importcsv()
	{
		$file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
		fgetcsv($file_data);
		while ($row = fgetcsv($file_data)) {
			$data[] = array(
				'Email' => $row[0],
			);
		}
		echo json_encode($data);
	}

	public function email_sample_csv()
	{
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=email_csv_sample.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('Email'));
		$data['email'] = array(
			'email' => "example@domain-name.com",
		);
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	public function sms_sample_csv()
	{
		header("Content-Type: text/csv; charset=utf-8");
		header("Content-Disposition: attachment; filename=sms_csv_sample.csv");
		$output = fopen("php://output", "w");
		fputcsv($output, array('Phonenumber'));
		$data['Phonenumber'] = array(
			'Phonenumber' => "0123456789",
		);
		foreach ($data as $row) {
			fputcsv($output, $row);
		}
		fclose($output);
	}

	public function send_multiple_link()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user');
		}
		$cq_res = $this->Adminmodel->check_quota_expire();
		if ($cq_res !== false) {
			$db_email = $cq_res->email;
			if ($this->session->userdata('rr_admin') == "0") {
				$this->quota_send_mail_expire($db_email);
				$this->session->set_flashdata('quota_expired', 'Quota has expired. Request has been sent to admin for renewal');
			} else if ($this->session->userdata('rr_admin') == "1") {
				$this->session->set_flashdata('quota_expired', 'Quota has expired');
			}
		} else {
			$emaildata = $_POST['emaildata'];
			$subj = $_POST['subj'];
			$bdy = $_POST['bdy'];
			$num = count($emaildata);
			$qbl_res = $this->Adminmodel->quota_bal_length();
			if ($qbl_res->bal < $num) {
				$this->session->set_flashdata('small_bal_length', 'Number of emails to be sent exceeds your remaining quota point of ' . $qbl_res->bal . ' .');
			} else {
				$mail_res = $this->send_multiple_link_email($emaildata, $subj, $bdy);
				if ($mail_res !== true) {
					$this->session->set_flashdata('link_send_err', $mail_res);
				} else {
					$res = $this->Usermodel->multiplemail_save_info($_POST['emaildata'], $_POST['subj'], $_POST['bdy'], $_POST['link_for']);
					if ($res !== true) {
						$this->session->set_flashdata('link_send_err', 'Error saving contacts to DATABASE.');
					} else {
						$length = count($emaildata);
						$cq_res = $this->Adminmodel->quota_update($length);
						if ($cq_res !== false) {
							$db_email = $cq_res->email;
							$this->quota_send_mail_expire($db_email);
							$this->session->set_flashdata('link_send_succ', 'Link sent successfully');
						} else {
							$this->session->set_flashdata('link_send_succ', 'Link sent successfully');
						}
					}
				}
			}
		}
	}

	public function send_multiple_link_email($emaildata, $subj, $bdy)
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

		$this->email->from('jvweedtest@gmail.com', 'Rating');
		$this->email->to(implode(",", $emaildata));
		$this->email->subject($subj);
		$this->email->message($bdy);

		if ($this->email->send()) {
			return true;
		} else {
			return $this->email->print_debugger();
		}
	}

	public function sms_send_link()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user');
		}
		$this->form_validation->set_rules('mobile', 'Mobile', 'required|trim|html_escape');
		$this->form_validation->set_rules('smsbdy', 'Body', 'required|trim|html_escape');

		if ($this->form_validation->run() == FALSE) {
			$this->rating();
		} else {
			$cq_res = $this->Adminmodel->check_quota_expire();
			if ($cq_res !== false) {
				$db_email = $cq_res->email;
				if ($this->session->userdata('rr_admin') == "0") {
					$this->quota_send_mail_expire($db_email);
					$this->session->set_flashdata('quota_expired', 'Quota has expired. Request has been sent to admin for renewal');
					redirect('user/account');
				} else if ($this->session->userdata('rr_admin') == "1") {
					$this->session->set_flashdata('quota_expired', 'Quota has expired');
					redirect('admin/pick_plan');
				}
			} else {
				$mobile = $this->input->post('mobile');
				$bdy = $this->input->post('smsbdy');

				$url = "http://onextelbulksms.in/shn/api/pushsms.php?usr=621665&key=010BrbJ20v1c2eCc8LGih6RlTIGqKN&sndr=KARUNJ&ph=+91" . $mobile . "&text=";
				$req = curl_init();
				$complete_url = $url . curl_escape($req, $bdy) . "&rpt=1";
				curl_setopt($req, CURLOPT_URL, $complete_url);
				$result = curl_exec($req);

				if ($result == false) {
					$this->session->set_flashdata('sms_link_send_err', 'Error sending SMS');
					redirect('user/rating');
				} else {
					$res = $this->Usermodel->sms_save_info();
					if ($res !== true) {
						$this->session->set_flashdata('link_send_err', 'Error saving contacts to DATABASE.');
						redirect('user/rating');
					} else {
						$length = '1';
						$cq_res = $this->Adminmodel->quota_update($length);
						if ($cq_res !== false) {
							$db_email = $cq_res->email;
							$db_mobile = $cq_res->mobile;
							$this->quota_send_mail_expire($db_email);
							$this->session->set_flashdata('sms_link_send_succ', 'SMS sent successfully');
							redirect('user/rating');
						} else {
							$this->session->set_flashdata('sms_link_send_succ', 'SMS sent successfully');
							redirect('user/rating');
						}
					}
				}
				curl_close($req);
			}
		}
	}

	public function sms_importcsv()
	{
		$file_data = fopen($_FILES['sms_csv_file']['tmp_name'], 'r');
		fgetcsv($file_data);
		while ($row = fgetcsv($file_data)) {
			$data[] = array(
				'Phonenumber' => $row[0],
			);
		}
		echo json_encode($data);
	}

	public function multiple_sms_send_link()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user');
		}
		$cq_res = $this->Adminmodel->check_quota_expire();
		if ($cq_res !== false) {
			$db_email = $cq_res->email;
			if ($this->session->userdata('rr_admin') == "0") {
				$this->quota_send_mail_expire($db_email);
				$this->session->set_flashdata('quota_expired', 'Quota has expired. Request has been sent to admin for renewal');
			} else if ($this->session->userdata('rr_admin') == "1") {
				$this->session->set_flashdata('quota_expired', 'Quota has expired');
			}
		} else {
			$mobiledata = $_POST['mobiledata'];
			$smsbdy = $_POST['smsbdy'];
			$num = count($mobiledata);
			$qbl_res = $this->Adminmodel->quota_bal_length();
			if ($qbl_res->bal < $num) {
				$this->session->set_flashdata('small_bal_length', 'Number of sms to be sent exceeds your remaining quota point of ' . $qbl_res->bal . ' .');
			} else {
				$url = "http://onextelbulksms.in/shn/api/pushsms.php?usr=621665&key=010BrbJ20v1c2eCc8LGih6RlTIGqKN&sndr=KARUNJ&ph=" . implode(",", $mobiledata) . "&text=";
				$req = curl_init();
				$complete_url = $url . curl_escape($req, $smsbdy) . "&rpt=1";
				curl_setopt($req, CURLOPT_URL, $complete_url);
				$result = curl_exec($req);

				if ($result === false) {
					$this->session->set_flashdata('sms_link_send_err', 'Error sending SMS');
				} else {
					$res = $this->Usermodel->multiplsms_save_info($_POST['mobiledata'], $_POST['smsbdy'], $_POST['link_for']);
					if ($res !== true) {
						$this->session->set_flashdata('link_send_err', 'Error saving contacts to DATABASE.');
					} else {
						$length = count($mobiledata);
						$cq_res = $this->Adminmodel->quota_update($length);
						if ($cq_res !== false) {
							$db_email = $cq_res->email;
							$db_mobile = $cq_res->mobile;
							$this->quota_send_mail_expire($db_email);
							$this->session->set_flashdata('sms_link_send_succ', 'SMS sent successfully');
						} else {
							$this->session->set_flashdata('sms_link_send_succ', 'SMS sent successfully');
						}
					}
				}
				curl_close($req);
			}
		}
	}

	public function account()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('loginfirst', 'Please login first');
			redirect('user');
		}
		$data['user'] = $this->Usermodel->user_total_ratings();
		$data['balance'] = $this->Usermodel->user_balance();
		$data['all_sms'] = $this->Usermodel->all_user_sms();
		$data['all_email'] = $this->Usermodel->all_user_email();
		$data['sent_links'] = $this->Usermodel->all_sent_links();
		//die(print_r($data['sent_links']));
		$this->load->view('templates/header');
		$this->load->view('users/account', $data);
		$this->load->view('templates/footer');
	}

	public function bar_data()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('user');
			exit();
		}
		$data['tl'] = $this->Usermodel->total_ratings();
		$data['tl1'] = $this->Usermodel->total_1();
		$data['tl2'] = $this->Usermodel->total_2();
		$data['tl3'] = $this->Usermodel->total_3();
		$data['tl4'] = $this->Usermodel->total_4();
		$data['tl5'] = $this->Usermodel->total_5();
		$data['token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function user_bar_data()
	{
		if (!$this->session->userdata('rr_logged_in')) {
			$this->session->set_flashdata('login_first', 'Please login first');
			redirect('user');
			exit();
		}
		$data = $this->Usermodel->user_total_ratings();
		$data->token = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function get_key($key)
	{
		$form_key = $this->Usermodel->get_key($key);
		if (!$form_key) {
			return false;
		} else {
			return $form_key;
		}
	}

	public function rp($key)
	{
		if (!$key) {
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		} else {
			$form_key = $this->get_key($key);
			if ($form_key !== $key) {
				//redirect($_SERVER['HTTP_REFERER']);
				exit();
			} elseif ($form_key == $key) {
				$data['form_key'] = $form_key;
				$this->load->view('users/rate_option', $data);
			}
		}
	}

	public function rate($key)
	{
		if (!$key) {
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		} else {
			$form_key = $this->get_key($key);
			if ($form_key !== $key) {
				//redirect($_SERVER['HTTP_REFERER']);
				exit();
			} elseif ($form_key == $key) {
				$data['form_key'] = $form_key;
				$this->load->view('users/official', $data);
				$this->load->view('templates/footer');
			}
		}
	}

	public function question($key)
	{
		if (!$key) {
			redirect($_SERVER['HTTP_REFERER']);
			exit();
		} else {
			$form_key = $this->get_key($key);
			if ($form_key !== $key) {
				//redirect($_SERVER['HTTP_REFERER']);
				exit();
			} elseif ($form_key == $key) {
				$data['form_key'] = $form_key;
				$this->load->view('users/question_rate', $data);
				$this->load->view('templates/footer');
			}
		}
	}

	public function save_questions($key)
	{
		if (!$key) {
			redirect($_SERVER['HTTP_REFERER']);
			exit;
		} else {
			$cq_res = $this->Usermodel->save_questions($key);
			// $cq_res = true;
			if ($cq_res !== true) {
				$this->session->set_flashdata('ques_r_err', 'Error collecting your data');
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}else{
				$this->session->set_flashdata('ques_r_succ', 'Thanks for your feedback');
				redirect($_SERVER['HTTP_REFERER']);
				exit;
			}
		}
	}

	public function rating_store_review()
	{
		$cq_res = $this->Adminmodel->check_quota_expire();
		if ($cq_res !== false) {
			$db_email = $cq_res->email;
			$this->quota_send_mail_expire($db_email);
			return false;
		} else {
			$res = $this->Usermodel->rating_store_review($_POST['starv'], $_POST['msg'], $_POST['name'], $_POST['mobile'], $_POST['tbl_name'], $_POST['form_key']);
		}
	}

	public function support()
	{
		$this->form_validation->set_rules('name', 'Name', 'trim|html_escape');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|html_escape');
		$this->form_validation->set_rules('msg', 'Message', 'required|trim|html_escape');

		if ($this->form_validation->run() === FALSE) {
			$this->load->view('templates/header');
			$this->load->view('users/contactus');
			$this->load->view('templates/footer');
		} else {
			$name = $this->input->post('name');
			$user_mail = $this->input->post('email');
			$bdy = $this->input->post('msg');
			$mail_res = $this->support_mail($name, $user_mail, $bdy);
			if ($mail_res !== true) {
				$this->session->set_flashdata('cntc_us_err', 'Error sending your message');
				redirect($_SERVER['HTTP_REFERER']);
			} else {
				$this->session->set_flashdata('cntc_us_succ', 'Your message has been sent. We will get back to you as soon as possible');
				redirect($_SERVER['HTTP_REFERER']);
			}
		}
	}

	public function support_mail($name, $user_mail, $bdy)
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
		if ($user_mail) {
			$subj = "Support mail from " . $user_mail;
		} else if (!$user_mail) {
			$subj = "Support mail";
		}

		$this->email->from('jvweedtest@gmail.com', 'Rating');
		$this->email->to('olatayoefficient@gmail.com');
		$this->email->subject($subj);
		$this->email->message($bdy);

		if ($this->email->send()) {
			return true;
		} else {
			return $this->email->print_debugger();
		}
	}
}
