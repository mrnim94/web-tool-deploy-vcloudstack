<?php
error_reporting(0);
use phpseclib\Net\SSH2;
use phpseclib\Net\SFTP;
use phpseclib\Crypt\RSA;
/**
 *
 */
class Portal extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		//load file Admin_model.php
		$this->load->model('vmportal_model');
		$this->load->model('mykey_model');
	}

	function one()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
		//Nếu mà có giữ liệu POST lên thì kiểm tra
		if($this->input->post())
		{
			$this->form_validation->set_rules('ip_portal','IP VM Portal','required');
			// các tập luật các nhau bằng dấu gạch giữa và phải ghi sát nhau
			//nhập liệu chính sách các yêu cầu
			if($this->form_validation->run())
			{
				$ip_portal = $this->input->post('ip_portal');
				//Remote server's ip address or hostname
				$this->check_ssh($ip_portal);
			}
		}

		//lấy ra nội dung của biến message thông báo và hiển thị
		$message = $this->session->flashdata('message');
		$this->data['message'] = $message;

		//gọi file hiển thị
		$this->data['temp'] = 'admin/portal/one';
		$this->load->view('admin/main', $this->data);
	}

	function two()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
		//Nếu mà có giữ liệu POST lên thì kiểm tra
		if($this->input->post())
		{
			$this->form_validation->set_rules('ip_portal_master','IP VM Portal Master','required');
			$this->form_validation->set_rules('ip_portal_backup','IP VM Portal Backup','required');
			// các tập luật các nhau bằng dấu gạch giữa và phải ghi sát nhau
			//nhập liệu chính sách các yêu cầu
			if($this->form_validation->run())
			{
				$ip_portal = array();
				$ip_portal['master'] = $this->input->post('ip_portal_master');
				$ip_portal['backup'] = $this->input->post('ip_portal_backup');
				//Remote server's ip address or hostname
				$this->check_ssh_list($ip_portal);
			}
		}

		//lấy ra nội dung của biến message thông báo và hiển thị
		$message = $this->session->flashdata('message');
		$this->data['message'] = $message;

		//gọi file hiển thị
		$this->data['temp'] = 'admin/portal/two';
		$this->load->view('admin/main', $this->data);
	}


	function install_one()
	{
		if (empty($this->session->userdata("incognito_portal"))) {
			redirect(admin_url('portal/one'));
		}
		else
		{
			if($this->input->post())
			{
				if ($this->input->post('option_db') == 'update')
				{
					$this->load->library('upload_library');
					$upload_path = './upload/csdl';
					$data = $this->upload_library->upload($upload_path, 'file_database');
				}
				else
				{
					// $result = $this->cli_config_import_db()
				}
			}
		}
		//gọi file hiển thị
		$this->data['temp'] = 'admin/portal/install_one';
		$this->load->view('admin/main', $this->data);
	}

	function install_two()
	{
		//gọi file hiển thị
		$this->data['temp'] = 'admin/portal/install_two';
		$this->load->view('admin/main', $this->data);
	}

	function config_portal()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_config_portal = $this->input->post('json_info_config_portal');
			if ($action == "config_portal")
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$ip = $this->vmportal_model->get_info_rule($where, $field='ip_portal');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$range_ports = array(3306);
						$result0 = $this->check_port($ip->ip_portal, $range_ports);
						$info_config_portal = json_decode($json_info_config_portal);
						echo $result1 = $this->cli_config_portal($ssh, $ip->ip_portal, $info_config_portal, $result0);
					}
				}
				else
				{
					redirect(admin_url('portal/one'));
					// return false;
				}
			}
		}
	}

	function config_2portal()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_config_2portal = $this->input->post('json_info_config_2portal');
			if ($action == "config_2portal")
			{
				$input = array();
				$where = array('incognito_portal' => $this->session->userdata("incognito_portal"));
				$input['select'] = 'ability_portal, ip_portal';
				$input['where'] = $where;
				if ($this->vmportal_model->check_exists($where))
				{
					$info_vms = $this->vmportal_model->get_list($input);
					//$ip = $this->vmportal_model->get_info_rule($where, $field='ip_portal');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$info_config_2portal = json_decode($json_info_config_2portal);
						$range_ports = array(3306);
						$result_check_port = 0;
						$ips_ability = array();
						foreach ($info_vms as $items)
						{
							if ($this->check_port($items->ip_portal, $range_ports) == 'open')
							{
								$result_check_port += 1;
								$items->mysql = 'open';
							}
							else
							{
								$items->mysql = 'not_responding';
							}
							//////////////////////////////////////////
							if ($items->ability_portal == 'master')
							{
								$ips_ability['master'] = $items->ip_portal;
							}
							else
							{
								$ips_ability['backup'] = $items->ip_portal;
							}
						}
						if ($result_check_port == 0)
						{
							$result0 = "not_responding";
						}
						else
						{
							$result0 = "open";
						}
						echo $result1 = $this->cli_config_2portal($ssh, $ips_ability, $info_config_2portal, $result0);
					}

				}
				else
				{
					redirect(admin_url('portal/two'));
					// return false;
				}
			}
		}
	}

	function install_portal_one()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == "install_portal_one")
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$data = array(
						'log_ansible_for_portal' => 'log_deploy_portal_'.time().'.nim',
						);
						if ($this->vmportal_model->update_rule($where ,$data))
						{
							$link_ansible = "ansible-playbook /home/auto_deploy_portal/deploy-sme_vstorage/playbook.yml -i /home/auto_deploy_portal/deploy-sme_vstorage/inventory.txt > "."/home/auto_deploy_portal/log_ansible_for_portal/log_deploy_portal_".time().".nim";
						echo $result = $this->cli_install_portal($ssh, $link_ansible);
						}
					}
				}
				else
				{
					redirect(admin_url('portal/one'));
					return false;
				}
			}
		}
	}

	function install_portal_two()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == "install_portal_two")
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$data = array(
						'log_ansible_for_portal' => 'log_deploy_2portal_'.time().'.nim',
						);
						if ($this->vmportal_model->update_rule($where ,$data))
						{
							$link_ansible = "ansible-playbook /home/auto_deploy_2portal/deploy-sme_vstorage/playbook.yml -i /home/auto_deploy_2portal/deploy-sme_vstorage/inventory.txt > "."/home/auto_deploy_2portal/log_ansible_for_portal/log_deploy_2portal_".time().".nim";
						echo $result = $this->cli_install_portal($ssh, $link_ansible);
						}
					}
				}
				else
				{
					redirect(admin_url('portal/one'));
					return false;
				}
			}
		}
	}

	function log_install_portal_one()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == "log_install_portal_one")
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$log = $this->vmportal_model->get_info_rule($where, $field='log_ansible_for_portal');
						echo $result = $this->cli_log_install_portal_one($ssh, $log->log_ansible_for_portal);
					}
				}
				else
				{
					redirect(admin_url('portal/one'));
					// return false;
				}
			}
		}
	}

	function log_install_portal_two()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == "log_install_portal_two")
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$log = $this->vmportal_model->get_info_rule($where, $field='log_ansible_for_portal');
						echo $result = $this->cli_log_install_portal_two($ssh, $log->log_ansible_for_portal);
					}
				}
				else
				{
					redirect(admin_url('portal/two'));
					// return false;
				}
			}
		}
	}

	function add_ssl()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_add_ssl = $this->input->post('json_add_ssl');
			if ($action == "add_ssl")
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$add_ssl = json_decode($json_add_ssl);
						echo $result = $this->cli_add_ssl($ssh, $add_ssl);
					}
				}
			}
			else
			{
				$where = array(
				'incognito_portal' => $this->session->userdata("incognito_portal"),
				);
				if ($this->vmportal_model->check_exists($where))
				{
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($my_info->ip_server_ansible ,22);
					$key = new RSA();
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$add_ssl = json_decode($json_add_ssl);
						echo $result = $this->cli_add_ssl_2portal($ssh, $add_ssl);
					}
				}
			}
		}
	}

/////////////////////////////////////////////////////////////
/////////////Thực  hiển kiểm tra ssh vào portal//////////////
/////////////////////////////////////////////////////////////
	private function check_ssh($ip='', $ability='')
	{
		$id = 1;
		$my_info = $this->mykey_model->get_info($id);
		$ssh = new SSH2($ip,22);
		$key = new RSA();
		$key->loadKey($my_info->privatekey);
		if (!$ssh->login('root', $key)) {
			//exit('Login Failed');
		    http_response_code(503);
		    $this->session->set_flashdata('message', 'Không ssh được vào VM portal, kiểm tra lại vm Portal');
		    redirect(admin_url('portal/one'));
			return false;
		}
		else
		{
			$incognito = $this->generate_string($ip.'nim');
			$where = array('ip_portal' => $ip);
			// tạo biến data để lưu dữ liệu
			$data = array(
					'ip_portal'     => $ip,
					'incognito_portal' => $incognito,
					'hostname_portal' => trim($ssh->exec("hostname")),
					'ability_portal' => $ability,
					);
			if ($this->vmportal_model->check_exists($where))
			{
				//tiến hay cập nhật thông tin
				if ($this->vmportal_model->update_rule($where ,$data))
				{
					//tạo ra thông báo khi thêm mới admin thành công gửi sang view
					$this->session->set_flashdata('message', 'Cập nhật thông tin thành công!');
					redirect(admin_url('portal/install_one'));
				}
				else
				{
					//tạo ra thông báo khi thêm mới admin không thành công gửi sang view
					$this->session->set_flashdata('message', 'Cập nhật thông tin không thành công!');
					return false;
				}
			}
			else
			{
				//thêm mới cơ sở dữ liệu bằng function create trong physicalnode_model
				if ($this->vmportal_model->create($data))
				{
					//tạo ra thông báo khi thêm mới addmin thành công gửi sang view
					$this->session->set_flashdata('message', 'Hệ thống đã ghi nhận thông tin của bạn');
					redirect(admin_url('portal/install_one'));
				}
				else
				{
					//tạo ra thông báo khi thêm mới addmin ko thành công gửi sang view
					$this->session->set_flashdata('message', 'Hệ thống chưa ghi nhận thông tin của bạn');
					return false;
				}
			}
		}
	}

	private function check_ssh_list($array_ip='')
	{
		$id = 1;
		$my_info = $this->mykey_model->get_info($id);
		$check = 0;
		$ip_json = json_encode($array_ip);
		$incognito = $this->generate_string($ip_json.'nim');
		foreach ($array_ip as $ability => $ip)
		{
			$ssh = new SSH2($ip,22);
			$key = new RSA();
			$key->loadKey($my_info->privatekey);
			if (!$ssh->login('root', $key))
			{
				//exit('Login Failed');
			    http_response_code(503);
			    $this->session->set_flashdata('message', 'Không ssh được vào VM '.$ability.', kiểm tra lại vm Portal '.$ability);
			    redirect(admin_url('portal/two'));
				return false;
			}
			else
			{
				$where = array('ip_portal' => $ip);
				// tạo biến data để lưu dữ liệu
				$data = array(
						'ip_portal'     => $ip,
						'incognito_portal' => $incognito,
						'hostname_portal' => trim($ssh->exec("hostname")),
						'ability_portal' => $ability,
						);
				if ($this->vmportal_model->check_exists($where))
				{
					//tiến hay cập nhật thông tin
					if ($this->vmportal_model->update_rule($where ,$data))
					{
						//tạo ra thông báo khi thêm mới admin thành công gửi sang view
						$this->session->set_flashdata('message', 'Cập nhật thông tin thành công!');
						$check += 1;
					}
					else
					{
						//tạo ra thông báo khi thêm mới admin không thành công gửi sang view
						$this->session->set_flashdata('message', 'Cập nhật thông tin không thành công!');
						return false;
					}
				}
				else
				{
					//thêm mới cơ sở dữ liệu bằng function create trong physicalnode_model
					if ($this->vmportal_model->create($data))
					{
						//tạo ra thông báo khi thêm mới addmin thành công gửi sang view
						$this->session->set_flashdata('message', 'Hệ thống đã ghi nhận thông tin của bạn');
						$check += 1;
					}
					else
					{
						//tạo ra thông báo khi thêm mới addmin ko thành công gửi sang view
						$this->session->set_flashdata('message', 'Hệ thống chưa ghi nhận thông tin của bạn');
						return false;
					}
				}
			}
		}
		if ($check == 2)
		{
			redirect(admin_url('portal/install_two'));
		}
		else
		{
			redirect(admin_url('portal/two'));
		}
	}

	private function generate_string($input, $strength = 16)
	{
		$input_length = strlen($input);
	    $random_string = '';
	    for($i = 0; $i < $strength; $i++) {
	        $random_character = $input[mt_rand(0, $input_length - 1)];
	        $random_string .= $random_character;
	    }
	    $this->session->set_userdata('incognito_portal', $random_string);
	    return $random_string;
	}

/////////////////////////////////////////////////////////////
/////////////Thực  hiển kiểm tra port 3306 cua VM//////////////
/////////////////////////////////////////////////////////////
	/**
	 * @host  ip hoặc name-server của node cần check
	 * @ports  kiểu array $ports = array(21, 25, 80, 81, 110, 143, 443, 587, 2525, 3306);
	 * @trả về ok hay ko?
	 */
	private function check_port($host='', $ports='')
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', -1);

		foreach ($ports as $port)
		{
		    $connection = @fsockopen($host, $port, $errno, $errstr, 2);

		    if (is_resource($connection))
		    {
		        //echo '<h2>' . $host . ':' . $port . ' ' . '(' . getservbyport($port, 'tcp') . ') is open.</h2>' . "\n";

		        fclose($connection);
		        return 'open';
		    }
		    else
		    {
		        //echo '<h2>' . $host . ':' . $port . ' is not responding.</h2>' . "\n";
		        return 'not_responding';
		    }
		}
	}


	//////////////////////////////////////////////////////////
	//////////////thực hiện command //////////////////////////
	//////////////////////////////////////////////////////////
	///
	///
	////////////////thực hiện lấy log deploy portal//////
	private function cli_log_install_portal_one($ssh='', $log)
	{
		echo $ssh->exec("cat /home/auto_deploy_portal/log_ansible_for_portal/".$log);
	}

	private function cli_log_install_portal_two($ssh='', $log)
	{
		echo $ssh->exec("cat /home/auto_deploy_2portal/log_ansible_for_portal/".$log);
	}
	///
	////////////////thực hiện chạy lênh ansible cài dặt portal/////////
	private function cli_install_portal($ssh='', $link_ansible='')
	{
		echo $ssh->exec($link_ansible);
		return "chạy ansible xong";
	}


	private function cli_config_portal($ssh='', $ip_portal='', $info_config_portal='', $status_port_db='')
	{
		//////////////////////đồng bộ config mới//////////
		$ssh->exec("rsync -av /usr/local/src/config_deploy_portal/deploy-sme_vstorage/ /home/auto_deploy_portal/deploy-sme_vstorage/");

		//////////////////////config thông tin keepalive//////////
		$ssh->exec('sed -i -e "s/eth_nim/'.$info_config_portal[0].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/vip_nim/'.$ip_portal.'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/vip_master_nim/'.$ip_portal.'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/vip_backup_nim/'.$ip_portal.'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/vip_master_nim/'.$ip_portal.'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/import_db.sh');

		//////////////////cấu hình ansible_host//////////////////////////
		$ssh->exec('sed -i -e "s/ansible_host_nim/'.$ip_portal.'/g" /home/auto_deploy_portal/deploy-sme_vstorage/inventory.txt');

		/////////////////////thay đổi IP kết nối đến hệ thống ảo hóa///////////////////////////////
		$ssh->exec('sed -i -e "s/ip_virtual_server/'.$info_config_portal[1].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/default.conf');
		$ssh->exec('sed -i -e "s/ip_virtual_server/'.$info_config_portal[1].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme.api.properties');

		///////////////////thay đổi thông tin image//////////////////////////////////////
		$ssh->exec('sed -i -e "s/sme_api_nim/'.$info_config_portal[2].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/sme_vstorage_nim/'.$info_config_portal[3].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/portal_sme_nim/'.$info_config_portal[4].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');

		////////////////////////thay đổi server name portal/////////////////////////////////
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_portal[5].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/application.properties');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_portal[5].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/default.conf');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_portal[5].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/env.properties');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_portal[5].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme.api.properties');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_portal[5].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme_api_nim.sql');

		/////////////////////thay đổi thông tin support/////////////////////////////////////
		$ssh->exec('sed -i -e "s/hotline_support_nim/'.$info_config_portal[6].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme_api_nim.sql');
		$ssh->exec('sed -i -e "s/skype_support_nim/'.$info_config_portal[7].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme_api_nim.sql');
		$ssh->exec('sed -i -e "s/email_support_nim/'.$info_config_portal[8].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme_api_nim.sql');
		$ssh->exec('sed -i -e "s/full_name_nim/'.$info_config_portal[9].'/g" /home/auto_deploy_portal/deploy-sme_vstorage/config/sme_api_nim.sql');

		////////thực hiện xóa câu lệnh import database và replicate DB
		if ($status_port_db == 'open')
		{
			$string_array = array(
				'- import_db_default',
				'- replicate_db');
			$link_playbook = '/home/auto_deploy_portal/deploy-sme_vstorage/playbook.yml';
			foreach ($string_array as $string)
			{
			$ssh->exec('sed -i -e "s/'.$string.'/ /g" '.$link_playbook);
			}
			return "File config dùng để upgrade portal";
		}
		else
		{
			return "File config dùng để cài mới portal";
		}
	}

	private function cli_config_2portal($ssh='', $ip_portals='', $info_config_2portal='', $status_port_db='')
	{
		//////////////////////đồng bộ config mới//////////
		$ssh->exec("rsync -av /usr/local/src/config_deploy_2portal/deploy-sme_vstorage/ /home/auto_deploy_2portal/deploy-sme_vstorage/");

		//////////////////////config thông tin keepalive//////////
		$ssh->exec('sed -i -e "s/eth_master_nim/'.$info_config_2portal[1].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/host_vars/master_portal');
		$ssh->exec('sed -i -e "s/eth_backup_nim/'.$info_config_2portal[2].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/host_vars/backup_portal');
		$ssh->exec('sed -i -e "s/vip_master_nim/'.$info_config_2portal[3].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/ip_master_nim/'.$ip_portals['master'].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/ip_backup_nim/'.$ip_portals['backup'].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');

		//////////////câu hình file import DB////////////////////////
		$ssh->exec('sed -i -e "s/vip_master_nim/'.$info_config_2portal[3].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/import_db.sh');

		//////////////Cấu hình vstorage truy cấp DB////////////////////////
		$ssh->exec('sed -i -e "s/vip_master_nim/'.$info_config_2portal[3].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/application.properties');

		//////////////replicate DB////////////////////////
		$ssh->exec('sed -i -e "s/ip_master_nim/'.$ip_portals['master'].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/replicate_db.sh');
		$ssh->exec('sed -i -e "s/ip_backup_nim/'.$ip_portals['backup'].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/replicate_db.sh');

		//////////////////cấu hình ansible_host//////////////////////////
		$ssh->exec('sed -i -e "s/ip_master_nim/'.$ip_portals['master'].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/inventory.txt');
		$ssh->exec('sed -i -e "s/ip_backup_nim/'.$ip_portals['backup'].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/inventory.txt');

		/////////////////////thay đổi IP kết nối đến hệ thống ảo hóa///////////////////////////////
		$ssh->exec('sed -i -e "s/ip_virtual_server/'.$info_config_2portal[0].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/default.conf');
		$ssh->exec('sed -i -e "s/ip_virtual_server/'.$info_config_2portal[0].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme.api.properties');

		///////////////////thay đổi thông tin image//////////////////////////////////////
		$ssh->exec('sed -i -e "s/sme_api_nim/'.$info_config_2portal[4].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/sme_vstorage_nim/'.$info_config_2portal[5].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');
		$ssh->exec('sed -i -e "s/portal_sme_nim/'.$info_config_2portal[6].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/group_vars/portal_vcloudstack.yml');

		////////////////////////thay đổi server name portal/////////////////////////////////
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_2portal[7].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/application.properties');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_2portal[7].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/default.conf');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_2portal[7].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/env.properties');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_2portal[7].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme.api.properties');
		$ssh->exec('sed -i -e "s/server_name_portal_nim/'.$info_config_2portal[7].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme_api_nim.sql');

		/////////////////////thay đổi thông tin support/////////////////////////////////////
		$ssh->exec('sed -i -e "s/hotline_support_nim/'.$info_config_2portal[8].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme_api_nim.sql');
		$ssh->exec('sed -i -e "s/skype_support_nim/'.$info_config_2portal[9].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme_api_nim.sql');
		$ssh->exec('sed -i -e "s/email_support_nim/'.$info_config_2portal[10].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme_api_nim.sql');
		$ssh->exec('sed -i -e "s/full_name_nim/'.$info_config_2portal[11].'/g" /home/auto_deploy_2portal/deploy-sme_vstorage/config/sme_api_nim.sql');

		////////thực hiện xóa câu lệnh import database và replicate DB
		if ($status_port_db == 'open')
		{
			$string_array = array(
				'- import_db_default',
				'- replicate_db');
			$link_playbook = '/home/auto_deploy_2portal/deploy-sme_vstorage/playbook.yml';
			foreach ($string_array as $string)
			{
			$ssh->exec('sed -i -e "s/'.$string.'/ /g" '.$link_playbook);
			}
			return "File config dùng để upgrade portal";
		}
		else
		{
			return "File config dùng để cài mới portal";
		}
	}

	private function cli_add_ssl($ssh, $add_ssl)
	{
		$file_ssl=$add_ssl[0]."\n".$add_ssl[1]."\n";
		$ssh->exec("cat > /home/auto_deploy_portal/deploy-sme_vstorage/config/localhost.pem <<EOF
".$file_ssl."EOF
");
	}

	private function cli_add_ssl_2portal($ssh, $add_ssl)
	{
		$file_ssl=$add_ssl[0]."\n".$add_ssl[1]."\n";
		$ssh->exec("cat > /home/auto_deploy_2portal/deploy-sme_vstorage/config/localhost.pem <<EOF
".$file_ssl."EOF
");
		return $file_ssl;
	}
}

?>