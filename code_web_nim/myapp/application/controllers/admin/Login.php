<?php
	/**
	 *
	 */
	class Login extends MY_Controller
	{
		function index()
		{
			$this->load->library('form_validation');
			$this->load->helper('form');
			error_reporting(0);
			if ($this->input->post())
			{
				$this->form_validation->set_rules('login', 'Đăng nhập', 'callback_check_login_by_ldap');
				if ($this->form_validation->run())
				{
					//tạo ra biến session với key = 'login' và giá trị là true
					$this->session->set_userdata('login', true);
					if($this->session->userdata('redirect_url'))
					{
					   $url = $this->session->userdata('redirect_url');
					   redirect($url);
					}
					else
					redirect(admin_url('home'));
				}
			}

			$this->load->view('admin/login/index');
		}

		/*
			Kiểm tra user name và password
		 */
		function check_login()
		{
			$username = $this->input->post('username');
			$password = md5($this->input->post('password'));

			$this->load->model('admin_model');
			$where = array(
							'username' => $username,
							'password' => $password,
							);
			$admin = $this->admin_model->get_info_rule($where);
			if ($admin)
			{

				//tạo ra biến session để lưu thông tin user
				$this->session->set_userdata('admin_name', $admin->name);
				$this->session->set_userdata('admin_image', $admin->image_link);
				return true;
			}
			$this->form_validation->set_message(__FUNCTION__, 'Tài khoản hoặc mật khẩu không đúng!');
			return false;
		}

		/**
		 * Kiểm tra tài khoản bằng LDAP
		 */
		function check_login_by_ldap()
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$server='10.36.37.34';
			$ds=ldap_connect($server);  // assuming the LDAP server is on this host
			$domain=trim('mptelecom\ ').$username;
			if ($ds)
			{
			    // bind with appropriate dn to give update access
			    $r=ldap_bind($ds, $domain, $password);
			    if(!$r)
			    {
			    	$this->form_validation->set_message(__FUNCTION__, 'Tài khoản hoặc mật khẩu không đúng!');
			    	return false;

			    }
			    else
			    {
			    	$this->load->model('admin_model');
					$where = array(
									'username' => $username,
									'status' => 1,
									);
					$admin = $this->admin_model->get_info_rule($where);
					if ($admin)
					{

						//tạo ra biến session để lưu thông tin user
						$this->session->set_userdata('admin_name', $admin->name);
						$this->session->set_userdata('admin_image', $admin->image_link);
						return true;
					}
					else
					{
						$this->form_validation->set_message(__FUNCTION__, 'Tài khoản chưa được phép truy cập!');
			    		return false;
					}
					ldap_close($ds);
			    }
			    ldap_close($ds);
			}
			else
			{
				$this->form_validation->set_message(__FUNCTION__, 'Kết nối xác thực bị lỗi!');
			    return false;
			}
		}
	}
 ?>