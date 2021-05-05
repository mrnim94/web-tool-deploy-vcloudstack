<?php
/**
 * Cấu hình và cài đặt cho node vật lý
 */
error_reporting(0);

use phpseclib\Net\SSH2;
use phpseclib\Net\SFTP;
use phpseclib\Crypt\RSA;

class Node extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		//load file Admin_model.php
		$this->load->model('physicalnode_model');
		$this->load->model('mykey_model');
	}
	/**
	 * Khái báo thông tin 1 node và thực hiện kiểm tra ssh_key
	 */
	function one()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
		//Nếu mà có giữ liệu POST lên thì kiểm tra
		if($this->input->post())
		{
			$this->form_validation->set_rules('ip_node','IP Node Vật lý','required');
			$this->form_validation->set_rules('pass_root','Pass Root của Node Vật lý','required');
			// các tập luật các nhau bằng dấu gạch giữa và phải ghi sát nhau

			//nhập liệu chính sách các yêu cầu
			if($this->form_validation->run())
			{
				$ip_node = $this->input->post('ip_node');
				$pass_root = $this->input->post('pass_root');

				//Remote server's ip address or hostname
				$this->check_ssh($ip_node,$pass_root);
			}
		}
		//lấy ra nội dung của biến message thông báo và hiển thị
		$message = $this->session->flashdata('message');
		$this->data['message'] = $message;

		//gọi file hiển thị
		$this->data['temp'] = 'admin/node/one';
		$this->load->view('admin/main', $this->data);
	}

	/**
	 * Khái báo thông tin 2 node và thực hiện kiểm tra ssh_key
	 */
	function two()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
		//Nếu mà có giữ liệu POST lên thì kiểm tra
		if($this->input->post())
		{
			$ip_node = $this->input->post('ip_node');
			$pass_root = $this->input->post('pass_root');
			$ability_node = $this->input->post('ability_node');

			//Remote server's ip address or hostname
			$this->check_ssh($ip_node, $pass_root, $ability_node);
		}

		//gọi file hiển thị
		$this->data['temp'] = 'admin/node/two';
		$this->load->view('admin/main', $this->data);
	}

	/**
	 * thực hiện cái app cho node ảo hóa vật lý (Non HA One node)
	 */
	function install_one()
	{
		if (empty($this->session->userdata("incognito_node"))) {
			redirect(admin_url('node/one'));
		}
		else
		{
			$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
			$password_node = $this->physicalnode_model->get_info_rule($where, $field='pass_root,ip_node');
			$this->cookie_api($password_node->ip_node,$password_node->pass_root);
		}
		//gọi file hiển thị
		$this->data['temp'] = 'admin/node/install_one';
		$this->load->view('admin/main', $this->data);
	}

	/**
	 * thực hiện cái app cho node ảo hóa vật lý (HA portal 2 node - Master)
	 */
	function install_2one_master()
	{
		if (empty($this->session->userdata("incognito_node"))) {
			redirect(admin_url('node/two'));
		}
		else
		{
			$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
			$password_node = $this->physicalnode_model->get_info_rule($where, $field='pass_root,ip_node');
			$this->cookie_api($password_node->ip_node,$password_node->pass_root);
		}
		//gọi file hiển thị
		$this->data['temp'] = 'admin/node/install_2node_master';
		$this->load->view('admin/main', $this->data);
	}

	/**
	 * thực hiện cái app cho node ảo hóa vật lý (HA portal 2 node - Master)
	 */
	function install_2one_slave()
	{
		if (empty($this->session->userdata("incognito_node"))) {
			redirect(admin_url('node/two'));
		}
		else
		{
			$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
			$password_node = $this->physicalnode_model->get_info_rule($where, $field='pass_root,ip_node');
			$this->cookie_api($password_node->ip_node,$password_node->pass_root);
		}
		//gọi file hiển thị
		$this->data['temp'] = 'admin/node/install_2node_slave';
		$this->load->view('admin/main', $this->data);
	}


	/*
	tạo mới cluster
	 */
	function create_cluster()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_name_cluster = $this->input->post('json_info_name_cluster');
			if ($action == "create_new_cluster")
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node');
					$info_name_cluster = json_decode($json_info_name_cluster);
					echo $result = $this->api_create_cluster($ip->ip_node,$info_name_cluster[0]);
				}
				else
				{
					redirect(admin_url('node/two'));
				}
			}
			else
				http_response_code(503);
				return false;
		}
	}

	function install_packages()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == "install_packages")
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($ip->ip_node,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						echo $result = $this->cli_packages_first($ssh);
					}
				}
				else
				{
					redirect(admin_url('node/one'));
				}
			}
			return false;
		}
		return false;
	}
	/**
	 * thực hiện create VM để tạo Portal
	 */
	function create_vm_ubuntu()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với

		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_vm_ubuntu = $this->input->post('json_info_vm_ubuntu');
			if ($action == "create_vm_ubuntu")
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,hostname_node');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($ip->ip_node,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
					    //exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$info_vm_ubuntu = json_decode($json_info_vm_ubuntu);
						echo $result_id_VM = $this->cli_create_vm_portal($ssh,$info_vm_ubuntu,$ip->ip_node);
						echo $result2 = $this->api_cloudinit($ip->ip_node,$ip->hostname_node,$result_id_VM);
						$ssh->exec('qm start '.$result_id_VM);
					}
				}
				else
				{
					redirect(admin_url('node/one'));
				}
			}
		}
	}

	function view_vm_ubuntu()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		$where = array(
		'incognito' => $this->session->userdata("incognito_node"),
		);
		if ($this->physicalnode_model->check_exists($where))
		{
			$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,hostname_node');
			$json_result = $this->api_view_vm($ip->ip_node, $ip->hostname_node);
			$this->data['list_vms'] = json_decode(($json_result));
			//Hiển thị ra phần view
			$this->load->view('admin/node/list_vms', $this->data);
		}
	}

	function console_vm()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == 'console_vm')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node');
					$cookie = array(
			        'name'   => 'PVEAuthCookie',
			        'value'  => get_cookie('PVEAuthCookie'),
			        'expire' => time()+7200,
			        'domain' => $ip->ip_node,
			        'path'   => '/',
			        );
			        set_cookie($cookie);
				}
			}
		}
	}

	function chia_o_dia()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');

			if ($action == 'chia_o_dia')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,hostname_node');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($ip->ip_node,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
						//exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$storage_name = 'local-lvm';
						$result0 = $this->api_get_status_storage($ip->ip_node, $ip->hostname_node, $storage_name);
						$result1 = $this->cli_chia_o_dia($ssh,$result0);
						echo $result2 = $this->api_create_storage($ip->ip_node,$ip->hostname_node);
					}
				}
			}
			else
			{
				redirect(admin_url('node/one'));
			}
		}
	}

	function chia_o_dia_and_update_storage()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');

			if ($action == 'chia_o_dia_and_update_storage')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,hostname_node');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($ip->ip_node,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
						//exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$storage_name = 'local-lvm';
						$result0 = $this->api_get_status_storage($ip->ip_node, $ip->hostname_node, $storage_name);
						$result1 = $this->cli_chia_o_dia($ssh,$result0);
						echo "local_data: ".round($result0, 2)." GB";
					}
				}
			}
			else
			{
				redirect(admin_url('node/one'));
			}
		}
	}

	function add_role()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_user_pool = $this->input->post('json_info_user_pool');
			if ($action == 'add_role')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					$ssh = new SSH2($ip->ip_node,22);
					$key = new RSA();
					//$key->setPassword($my_info->passphrase);
					$key->loadKey($my_info->privatekey);
					if (!$ssh->login('root', $key)) {
						//exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$info_user_pool = json_decode($json_info_user_pool);
						echo $result1 = $this->cli_add_role($ssh);
						echo $result2 = $this->api_create_user_supperadmin($ip->ip_node,$info_user_pool);
						echo $result3 = $this->api_create_pool($ip->ip_node,$info_user_pool[4]);
						echo $result4 = $this->api_create_group($ip->ip_node,$info_user_pool[4]);
						echo $result4 = $this->api_create_admin_and_user($ip->ip_node,$info_user_pool[4]);
					}
				}
			}
			else
			{
				redirect(admin_url('node/one'));
			}
		}
	}

	function create_vip_node()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_create_vip_node = $this->input->post('json_info_create_vip_node');
			if ($action == 'create_vip_node')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,ability_node');
					$id = 1;
					$my_info = $this->mykey_model->get_info($id);
					//////////////////////////////////////////
					$sftp = new SFTP($ip->ip_node,22);
					//////////////////////////////////////////
					$ssh = new SSH2($ip->ip_node,22);
					$key = new RSA();
					$key->loadKey($my_info->privatekey);

					////////////thực hiện sftp//////////
					if (!$sftp->login('root', $key)) {
				    //exit('Login Failed');
				    http_response_code(503);
					}
					else
					{
						echo $result1 = $this->sftp_vip_node($sftp, $ip->ability_node);
					}
					////////////thực hiện ssh//////////
					if (!$ssh->login('root', $key)) {
						//exit('Login Failed');
					    http_response_code(503);
					}
					else
					{
						$info_create_vip_node = json_decode($json_info_create_vip_node);
						echo $result1 = $this->cli_create_vip_node($ssh, $info_create_vip_node, $ip->ability_node);
					}
				}
				else
				{
					redirect(admin_url('node/two'));
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}


	function get_info_cluster()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == 'get_info_cluster')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,pass_root');
					$this->cookie_api($ip->ip_node, $ip->pass_root);
					echo $result1 = $this->api_get_info_cluster($ip->ip_node);
				}
				else
				{
					redirect(admin_url('node/two'));
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	function add_node_to_cluster()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_add_node_to_cluster = $this->input->post('json_info_add_node_to_cluster');
			if ($action == 'add_node_to_cluster')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node');
					$info_add_node_to_cluster = json_decode($json_info_add_node_to_cluster);
					echo $result1 = $this->api_add_node_to_cluster($ip->ip_node, $info_add_node_to_cluster);
				}
				else
				{
					redirect(admin_url('node/two'));
				}
			}
		}
	}

	function add_firewall_secgroup()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với
		if($this->input->post())
		{
			$action = $this->input->post('action');
			if ($action == 'add_firewall_secgroup')
			{
				$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
				if ($this->physicalnode_model->check_exists($where))
				{
					$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node,hostname_node');
					$enable_node = 0;
					$enable_cluster = 1;
					echo $result0 = $this->api_firewall_for_node($enable_node, $ip->hostname_node, $ip->ip_node);
					echo $result1 = $this->api_firewall_for_cluster($enable_cluster, $ip->ip_node);
					echo $result3 = $this->api_create_secgroup($ip->ip_node);

				}
			}
		}
	}

///////////////////////////////////////////////////////////////////
//////////////////////////========lấy key =====////////////////////
///////////////////////////////////////////////////////////////////


	private function check_ssh($ip='', $password='', $ability='')
	{
		$ssh = new SSH2($ip,22);
		if (!$ssh->login('root', $password)) {
			$this->session->set_flashdata('message', 'Mật khẩu hoặc IP server bạn cung cấp không đúng');
			return false;
		    //exit('Login Failed');
		}
		else{
			$incognito = $this->generate_string($ip.'nim');
			$where = array('ip_node' => $ip);
			// tạo biến data để lưu dữ liệu
			$data = array(
					'ip_node'     => $ip,
					'pass_root' => $password,
					'incognito' => $incognito,
					'hostname_node' => trim($ssh->exec("hostname")),
					'ability_node' => $ability,
					);
			if ($this->physicalnode_model->check_exists($where))
			{
				//tiến hay cập nhật thông tin
				if ($this->physicalnode_model->update_rule($where ,$data))
				{
					//tạo ra thông báo khi thêm mới admin thành công gửi sang view
					$this->session->set_flashdata('message', 'Cập nhật thông tin thành công!');
					$this->send_pubic_key($ssh, $ability);
				}
				else
				{
					//tạo ra thông báo khi thêm mới admin không thành công gửi sang view
					$this->session->set_flashdata('message', 'Cập nhật thông tin không thành công!');
					return false;
				}

				//nếu tồn tại trả về thông báo lỗi
				//$this->form_validation->set_message(__FUNCTION__, 'Ip của server này đã tồn tại trong hệ thống và thông tin sẽ được cập nhật');
			}
			else
			{
				//thêm mới cơ sở dữ liệu bằng function create trong physicalnode_model
				if ($this->physicalnode_model->create($data))
				{
					//tạo ra thông báo khi thêm mới addmin thành công gửi sang view
					$this->session->set_flashdata('message', 'Hệ thống đã ghi nhận thông tin của bạn');
					$this->send_pubic_key($ssh, $ability);
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

	private function send_pubic_key($ssh='', $ability='')
	{
		$id = 1;
		$my_info = $this->mykey_model->get_info($id);
		$ssh->exec("cat >> .ssh/authorized_keys <<EOF
".$my_info->publickey."
EOF
");
		if ($ability == 'node_master')
		{
			redirect(admin_url('node/install_2one_master'));
		}
		elseif ($ability == 'node_slave')
		{
			redirect(admin_url('node/install_2one_slave'));
		}
		else
		{
			redirect(admin_url('node/install_one'));
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
	    $this->session->set_userdata('incognito_node', $random_string);
	    return $random_string;
	}

//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////chạy command line//////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////
	private function cli_packages_first($ssh='')
	{
		$packages_first = "#!/bin/bash\nexec >/home/first_packages.log\nexec 2>&1\nrm -f /etc/apt/sources.list.d/pve-enterprise.list\napt-get update -y\napt-get install ifupdown2 -y\napt-get install curl gcc libssl-dev libnl-3-dev libnl-genl-3-dev libsnmp-dev keepalived -y\ncurl https://rclone.org/install.sh | bash\nmkdir -p /root/.config/rclone/\n";

		$disable_ipv6 = "net.ipv6.conf.all.disable_ipv6 = 1\nnet.ipv6.conf.default.disable_ipv6 = 1\nnet.ipv6.conf.lo.disable_ipv6 = 1\n";
		$ssh->exec("cat >> /etc/sysctl.d/99-sysctl.conf <<EOF
".$disable_ipv6."EOF
");
		$ssh->exec('sysctl -p');
		$ssh->exec("cat > /home/packages_first.sh <<EOF
".$packages_first."EOF
");
		$ssh->exec('bash /home/packages_first.sh');
		$cli_packages_first = "packages_first_ok";
		return $cli_packages_first;
	}

	private function cli_create_vm_portal($ssh='',$info_vm_ubuntu='',$ip='')
	{
		$ipconfig0 = $info_vm_ubuntu[0];
		$nameserver = $info_vm_ubuntu[1];
		$ciuser = $info_vm_ubuntu[2];
		$cipassword = $info_vm_ubuntu[3];
		$id_vm = $this->api_get_id_VM($ip);
		$file_bash = "#!/bin/bash\nexec >/home/create_vm_portal.log\nexec 2>&1\nqm create ".$id_vm." --name portal-vCloudStack --cores 8  --memory 8192 --net0 virtio,bridge=vmbr0 --description 'Đây là Portal vCloudStack SME Box'\nqm importdisk ".$id_vm." /usr/src/xenial-server-cloudimg-amd64-disk1.qcow2 local_data --format=qcow2\nqm set ".$id_vm." --scsihw virtio-scsi-pci --scsi0 local_data:".$id_vm."/vm-".$id_vm."-disk-0.qcow2 --ide2 local_data:cloudinit --boot c --bootdisk scsi0 --serial0 socket --vga serial0 --ciuser ".$ciuser." --cipassword ".$cipassword." --ipconfig0 ".$ipconfig0." --nameserver ".$nameserver."\necho 'tạo VM thành công';\n";
			$ssh->exec("cat > /home/create_vm_portal.sh <<EOF
".$file_bash."EOF
");
			$result_vm = $ssh->exec('bash /home/create_vm_portal.sh; pwd');
			return $id_vm;
	}


	private function cli_chia_o_dia($ssh='' ,$size_storage='')
	{
		$file_mount = "#mount by vcloudstack-nim\n/dev/pve/block-vcloudstack /home/block-vcloudstack/ ext4 rw 0 0";
		$ssh->exec("lvcreate -V ".round($size_storage, 2)."G -T pve/data --name block-vcloudstack");
		$ssh->exec("mkfs.ext4 /dev/pve/block-vcloudstack");
		$ssh->exec("mkdir /home/block-vcloudstack");
		$ssh->exec("mount /dev/pve/block-vcloudstack /home/block-vcloudstack/");
		$ssh->exec("cat >> /etc/fstab <<EOF
".$file_mount."EOF
");
		return 'chia thanh cong';
	}

	private function cli_add_role($ssh='')
	{
		$ssh->exec('pveum roleadd Super-Admin -privs "Datastore.Allocate Datastore.AllocateSpace Datastore.AllocateTemplate Datastore.Audit Group.Allocate Permissions.Modify Pool.Allocate Realm.Allocate Realm.AllocateUser Sys.Audit Sys.Console Sys.Modify Sys.PowerMgmt Sys.Syslog User.Modify VM.Allocate VM.Audit VM.Backup VM.Clone VM.Config.CDROM VM.Config.CPU VM.Config.Disk VM.Config.HWType VM.Config.Memory VM.Config.Network VM.Config.Options VM.Console VM.Migrate VM.Monitor VM.PowerMgmt VM.Snapshot VM.Snapshot.Rollback"');
		$ssh->exec('pveum roleadd Admin -privs "Datastore.Allocate Datastore.AllocateSpace Datastore.AllocateTemplate Datastore.Audit Pool.Allocate Sys.Audit Sys.Console Sys.Syslog VM.Allocate VM.Audit VM.Backup VM.Clone VM.Config.CDROM VM.Config.CPU VM.Config.Disk VM.Config.HWType VM.Config.Memory VM.Config.Network VM.Config.Options VM.Console VM.Migrate VM.Monitor VM.PowerMgmt VM.Snapshot VM.Snapshot.Rollback"');
		$ssh->exec('pveum roleadd User -privs "VM.Audit VM.Backup VM.Config.Options VM.Console VM.Monitor VM.PowerMgmt VM.Snapshot VM.Snapshot.Rollback Datastore.AllocateSpace Datastore.Audit VM.Config.Network"');

		return 'add role thành công';
	}

	private function cli_create_vip_node($ssh='', $info_create_vip_node='', $ability='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$data = array(
					'vip_cluster'     => $info_create_vip_node[0],
					);
			$this->physicalnode_model->update_rule($where ,$data);
			if ($ability == 'node_master')
			{
				$ssh->exec('sed -i -e "s/ABILITY_NIM/MASTER/g" /etc/keepalived/keepalived.conf');
			}
			else
			{
				$ssh->exec('sed -i -e "s/ABILITY_NIM/BACKUP/g" /etc/keepalived/keepalived.conf');
			}
			$ssh->exec('sed -i -e "s/VIP_NIM/'.$info_create_vip_node[0].'/g" /etc/keepalived/keepalived.conf');
			$ssh->exec('sed -i -e "s/PRIORITY_NIM/'.$info_create_vip_node[1].'/g" /etc/keepalived/keepalived.conf');
			$ssh->exec('service keepalived restart');
			return  $ssh->exec('service keepalived status');
		}
	}

///////////////////////////////////////////////////////////////////
//////////////////////////========Chạy SFTP=====///////////////////
///////////////////////////////////////////////////////////////////

	private function sftp_vip_node($sftp='' ,$ability='')
	{
		$sftp->put("/root/keepalived.state.sh", file_get_contents(FCPATH.'upload/vip_node/keepalived.state.sh'));
		$sftp->put("/root/check_node.sh", file_get_contents(FCPATH.'upload/vip_node/check_node.sh'));
		$sftp->put("/etc/keepalived/keepalived.conf", file_get_contents(FCPATH.'upload/vip_node/keepalived.conf'));
		$sftp->chmod(0777, '/root/check_node.sh');
		$sftp->chmod(0777, '/root/keepalived.state.sh');
		return "Hoàn thành sent file cho VIP";
	}

///////////////////////////////////////////////////////////////////
//////////////////////////========Chạy API=====////////////////////
///////////////////////////////////////////////////////////////////

	private function api_cloudinit($ip='', $hostname='', $id_VM='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$id = 1;
			$my_info = $this->mykey_model->get_info($id);
			$urlencode_sshkey = rawurlencode(utf8_encode($my_info->publickey));
			$form_params = array(
				'sshkeys' => $urlencode_sshkey,
				'onboot' => 1,
				'agent' => '1,fstrim_cloned_disks=1',
			);
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();
			$response = $client->request('POST', 'https://'.$ip.':8006/api2/json/nodes/'.$hostname.'/qemu/'.$id_VM.'/config', array('headers' => $headers, 'form_params' => $form_params, 'verify' => false ));
			echo $response->getBody();
			return "add publickey,onboot,agent thanh cong";
		}
		else
		{
			redirect(admin_url('node/one'));
		}
	}


	private function api_create_storage($ip='',$hostname='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$form_params = array(
				'type' => 'dir',
				'path' => '/home/block-vcloudstack/local_data',
				'storage' => 'local_data',
				'content' => 'images',
				'nodes' => "",
				'shared' => 0,
			);
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();
			$response = $client->request('POST', 'https://'.$ip.':8006/api2/json/storage', array('headers' => $headers, 'form_params' => $form_params, 'verify' => false ));
			$size_storage = round($this->api_get_status_storage($ip, $hostname, 'local_data'), 2);
			return "Storage local_data: ".$size_storage." GB";
		}
		else
		{
			redirect(admin_url('node/one'));
		}
	}

	private function api_create_user_supperadmin($ip='',$info_superadmin='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			//////////tạo user/////////////
			$form_params1 = array(
				'userid' => 'superadmin@pve',
				'password' => $info_superadmin[0],
				'lastname' => $info_superadmin[1],
				'firstname' => $info_superadmin[2],
				'email' => $info_superadmin[3],

			);
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();
			$response1 = $client->request('POST', 'https://'.$ip.':8006/api2/json/access/users', array('headers' => $headers, 'form_params' => $form_params1, 'verify' => false ));
			echo $response1->getBody();
			/////////////////////phân quyền////////////
			$form_params2 = array(
				'path' => '/',
				'users' => 'superadmin@pve',
				'roles' => 'Super-Admin',
			);
			$response2 = $client->request('PUT', 'https://'.$ip.':8006/api2/json/access/acl', array('headers' => $headers, 'form_params' => $form_params2, 'verify' => false ));
			echo $response2->getBody();
			return "tạo superadmin thành công";
		}
		else
		{
			redirect(admin_url('node/one'));
		}
	}

	private function api_create_admin_and_user($ip='',$company='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$form_params_admin = array(
				'userid' => 'adminx'.$company.'@pve',
				'password' => 'adminx'.$company.'!@#321',
				'lastname' => 'adminx'.$company,
				'firstname' => 'adminx'.$company,
				'email' => 'admin_'.$company.'@com.vn',
				'groups' => 'groupx'.$company,

			);

			$form_params_user = array(
				'userid' => 'userx'.$company.'@pve',
				'password' => 'userx'.$company.'!@#321',
				'lastname' => 'userx'.$company,
				'firstname' => 'userx'.$company,
				'email' => 'userx'.$company.'@com.vn',
				'groups' => 'groupx'.$company,

			);

			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$response_admin = $client->request('POST', 'https://'.$ip.':8006/api2/json/access/users', array('headers' => $headers, 'form_params' => $form_params_admin, 'verify' => false ));
			echo $response_admin->getBody();

			$response_user = $client->request('POST', 'https://'.$ip.':8006/api2/json/access/users', array('headers' => $headers, 'form_params' => $form_params_user, 'verify' => false ));
			echo $response_user->getBody();

			///////////////////////////////phân quyền/////////////////////
			$form_params_acl_admin = array(
				'path' => '/pool/poolx'.$company,
				'users' => 'adminx'.$company.'@pve',
				'roles' => 'Admin',
			);
			$response_acl_admin = $client->request('PUT', 'https://'.$ip.':8006/api2/json/access/acl', array('headers' => $headers, 'form_params' => $form_params_acl_admin, 'verify' => false ));
			echo $response_acl_admin->getBody();

			$form_params_acl_admin_access = array(
				'path' => '/access',
				'users' => 'adminx'.$company.'@pve',
				'roles' => 'Admin',
			);
			$response_acl_admin_access = $client->request('PUT', 'https://'.$ip.':8006/api2/json/access/acl', array('headers' => $headers, 'form_params' => $form_params_acl_admin_access, 'verify' => false ));
			echo $response_acl_admin_access->getBody();
			return "tạo user admin thành công";
		}
	}

	private function api_create_group($ip='',$company='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_group = array(
				'groupid' => 'groupx'.$company,
			);

			$response_group = $client->request('POST', 'https://'.$ip.':8006/api2/json/access/groups', array('headers' => $headers, 'form_params' => $form_params_group, 'verify' => false ));
			echo $response_group->getBody();
			/////////////////////////////phan quyen cho group//////////////////////////
			$form_params_acl_group = array(
				'path' => '/pool/poolx'.$company,
				'groups' => 'groupx'.$company,
				'roles' => 'User',
				'propagate' => 1,
			);
			$response_acl_group = $client->request('PUT', 'https://'.$ip.':8006/api2/json/access/acl', array('headers' => $headers, 'form_params' => $form_params_acl_group, 'verify' => false ));
			echo $response_acl_group->getBody();
			return "tạo group và acl group thành công";
		}
	}

	private function api_create_pool($ip='',$company='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_pool = array(
				'poolid' => 'poolx'.$company,
			);

			$response_group = $client->request('POST', 'https://'.$ip.':8006/api2/json/pools', array('headers' => $headers, 'form_params' => $form_params_pool, 'verify' => false ));
			echo $response_group->getBody();
			return "tạo pool thành công";
		}
	}

	private function api_create_cluster($ip='', $clustername='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_cluster = array(
				'clustername' => $clustername,
			);

			$response_cluster = $client->request('POST', 'https://'.$ip.':8006/api2/json/cluster/config', array('headers' => $headers, 'form_params' => $form_params_cluster, 'verify' => false ));
			echo $response_cluster->getBody();

			$data = array(
					'name_cluster'     => $clustername,
					);
			$this->physicalnode_model->update_rule($where ,$data);
			return "tạo cluster thành công";
		}
		else
		{
			redirect(admin_url('node/two'));
			return false;
		}
	}

	private function api_get_info_cluster($ip='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$response_info_cluster = $client->request('GET', 'https://'.$ip.':8006/api2/json/cluster/config/join', array('headers' => $headers, 'verify' => false ));
			$fingerprint = json_decode($response_info_cluster->getBody())->data->nodelist[0]->pve_fp;
			return $fingerprint;
		}
		else
		{
			redirect(admin_url('node/two'));
			return false;
		}
	}

	private function api_add_node_to_cluster($ip='', $info_add_node_to_cluster='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_add_node_to_cluster = array(
				'hostname' => $info_add_node_to_cluster[0],
				'password' => $info_add_node_to_cluster[1],
				'fingerprint' => $info_add_node_to_cluster[2],
			);

			$response_add_node_to_clusterr = $client->request('POST', 'https://'.$ip.':8006/api2/json/cluster/config/join', array('headers' => $headers, 'form_params' => $form_params_add_node_to_cluster, 'verify' => false ));
			sleep(90);
			echo $response_add_node_to_clusterr->getBody();
			return "add node vào cluster thành công";
		}
		else
		{
			redirect(admin_url('node/two'));
			return false;
		}
	}

	private function api_get_id_VM($ip='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$response_nextid = $client->request('GET', 'https://'.$ip.':8006/api2/json/cluster/nextid', array('headers' => $headers, 'verify' => false ));
			$id_VM = json_decode($response_nextid->getBody())->data;
			return $id_VM;
		}
	}

	private function api_get_status_storage($ip='', $hostname='', $storage_name='')
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();
			$response_status_storage = $client->request('GET', 'https://'.$ip.':8006/api2/json/nodes/'.$hostname.'/storage/'.$storage_name.'/status', array('headers' => $headers, 'verify' => false ));
			$storage_total = json_decode($response_status_storage->getBody())->data->total;
			$storage_total_GB = $storage_total/(1024*1024*1024);
			return $storage_total_GB;
		}
	}

	private function api_firewall_for_node($enable, $hostname, $ip)
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_firewall_for_node = array(
				'enable' => $enable,
			);
			$response_firewall_for_node = $client->request('PUT', 'https://'.$ip.':8006/api2/json/nodes/'.$hostname.'/firewall/options', array('headers' => $headers, 'form_params' => $form_params_firewall_for_node, 'verify' => false ));
			return "disable firewall node";
		}
	}

	private function api_firewall_for_cluster($enable, $ip)
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_firewall_for_cluster = array(
				'enable' => $enable,
			);
			$response_firewall_for_cluster = $client->request('PUT', 'https://'.$ip.':8006/api2/json/cluster/firewall/options', array('headers' => $headers, 'form_params' => $form_params_firewall_for_cluster, 'verify' => false ));
			return "enable firewall cluster";
		}
	}

	private function api_create_secgroup($ip )
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();
			$name_secgroup = 'secgroup_portal';
			try
			{
				$response_status_secgoup = $client->request('GET', 'https://'.$ip.':8006/api2/json/cluster/firewall/groups/'.$name_secgroup, array('headers' => $headers, 'verify' => false ));
				//echo $response_status_secgoup->getBody();
				echo "Secgroup này đã tồn tại";
			}
			catch (\GuzzleHttp\Exception\ServerException $e)
			{
				if ($e->getResponse()->getStatusCode() == 500)
				{
					$form_params_secgroup_for_cluster = array(
															'group' => $name_secgroup,
															'comment' => 'Secgroup dành cho Portal',
														);
					$response_secgroup_for_cluster = $client->request('POST', 'https://'.$ip.':8006/api2/json/cluster/firewall/groups', array('headers' => $headers, 'form_params' => $form_params_secgroup_for_cluster, 'verify' => false ));
					$this->api_add_rule_for_secgroup($ip,  $name_secgroup,  $type='in', $action='ACCEPT', $macro='HTTPS');
					$this->api_add_rule_for_secgroup($ip,  $name_secgroup,  $type='in', $action='ACCEPT', $macro='HTTP');
					return "create Secgroup";
				}
				else
				{
					return "Secgroup này đã tồn tại";
				}
			}
		}
	}

	private function api_add_rule_for_secgroup($ip,  $name_secgroup,  $type, $action, $macro)
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$form_params_add_rule_for_secgroup = array(
				'type' => $type,
				'action' => $action,
				'enable' => 1,
				'macro' => $macro,
				'log' => 'nolog',
			);
			$response_add_rule_for_secgroup = $client->request('POST', 'https://'.$ip.':8006/api2/json/cluster/firewall/groups/'.$name_secgroup, array('headers' => $headers, 'form_params' => $form_params_add_rule_for_secgroup, 'verify' => false ));
			return "add rule ok";
		}
	}

	private function api_view_vm($ip, $hostname)
	{
		$where = array(
				'incognito' => $this->session->userdata("incognito_node"),
				);
		if ($this->physicalnode_model->check_exists($where))
		{
			$headers['Content-Type'] = 'application/x-www-form-urlencoded';
			$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
			$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
			$client = new \GuzzleHttp\Client();

			$response_view_vm = $client->request('GET', 'https://'.$ip.':8006/api2/json/nodes/'.$hostname.'/qemu', array('headers' => $headers, 'verify' => false ));
			return $response_view_vm->getBody();
		}

	}


	// function api_proxmox()
	// {
	// 	$where = array(
	// 			'incognito' => $this->session->userdata("incognito_node"),
	// 			);
	// 	if ($this->physicalnode_model->check_exists($where))
	// 	{

	// 		$headers['Content-Type'] = 'application/x-www-form-urlencoded';
	// 		$headers['CSRFPreventionToken'] = $this->session->userdata("CSRFPreventionToken");
	// 		$headers['Cookie'] = 'PVEAuthCookie='.get_cookie('PVEAuthCookie');
	// 		$client = new \GuzzleHttp\Client();
	// 		$response = $client->request('GET', 'https://192.168.1.8:8006/api2/json/access/users', array('headers' => $headers, 'verify' => false ));
	// 		echo $response->getBody();
	// 	}
	// 	else
	// 	{
	// 		redirect(admin_url('node/one'));
	// 	}
	// }

	/**
	 *Tạo cookie mới
	 *
	 */
	private function cookie_api($ip='',$pass='')
	{
		$form_params = array(
			'username' => 'root',
			'password' => $pass,
			'realm' => 'pam',
		);

		$headers['Content-Type'] = 'application/x-www-form-urlencoded';
		$client = new \GuzzleHttp\Client();
		$response = $client->request('POST', 'https://'.$ip.':8006/api2/json/access/ticket', array('headers' => $headers, 'form_params' => $form_params, 'verify' => false));
		$getBody = json_decode($response->getBody());
		$cookie = array(
	        'name'   => 'PVEAuthCookie',
	        'value'  => $getBody->data->ticket,
	        'expire' => time()+7200,
        );
		set_cookie($cookie);
		$this->session->set_userdata('CSRFPreventionToken', $getBody->data->CSRFPreventionToken);
		return "get ticket thành công";

	}


} // END class

?>
