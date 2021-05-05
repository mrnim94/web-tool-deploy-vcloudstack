<?php
/**
 *
 */
use phpseclib\Net\SSH2;
use phpseclib\Crypt\RSA;
class Migrate extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('physicalnode_model');
		$this->load->model('mykey_model');
	}

	function windows()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
		//Nếu mà có giữ liệu POST lên thì kiểm tra
		if($this->input->post())
		{
			$ip_node = $this->input->post('ip_node');
			$pass_root = $this->input->post('pass_root');

			//Remote server's ip address or hostname
			$this->check_ssh($ip_node,$pass_root);
		}
		//gọi file hiển thị
		$this->data['temp'] = 'admin/migrate/windows';
		$this->load->view('admin/main', $this->data);
	}

	function build_vm_windows()
	{
		if (empty($this->session->userdata("incognito_node"))) {
			redirect(admin_url('migrate/windows'));
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
		$this->data['temp'] = 'admin/migrate/build_vm_windows';
		$this->load->view('admin/main', $this->data);
	}

	function checkandconvert_image_windows()
	{
		$this->load->library('form_validation');
		$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
		//Nếu mà có giữ liệu POST lên thì kiểm tra
		if($this->input->post())
		{
			$action = $this->input->post('action');
			$json_info_link_image_windows = $this->input->post('json_info_link_image_windows');
			if ($action == 'build_vm_windows')
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
					    return false;
					}
					else
					{
						$info_link_image_windows = json_decode($json_info_link_image_windows);
						$result_exist_image = $ssh->exec("[ -f ".$info_link_image_windows[0]." ] && echo 'exist' || echo 'not_exist'");
						if (trim($result_exist_image) == 'not_exist')
						{
							// không thấy file theo đường dẫn mà bạn cung cấp
							http_response_code(404);
							return false;
						}
						else
						{
							echo $result1 = $this->api_createVM_windows($ip->ip_node, $ip->hostname_node, $info_link_image_windows, $ssh);
						}

					}
				}
				else
				{
					http_response_code(401);
					return false;
				}
			}
			else
			{
				redirect(admin_url('migrate/build_vm_windows'));
				return false;
			}
		}
	}
	///////////////////////////////////////////////////////////////////
	//////////////////////////========lấy key =====///////////////////
	///////////////////////////////////////////////////////////////////


		private function check_ssh($ip='', $password='')
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
						);
				if ($this->physicalnode_model->check_exists($where))
				{
					//tiến hay cập nhật thông tin
					if ($this->physicalnode_model->update_rule($where ,$data))
					{
						//tạo ra thông báo khi thêm mới admin thành công gửi sang view
						$this->session->set_flashdata('message', 'Cập nhật thông tin thành công!');
						$this->send_pubic_key($ssh);
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
						$this->send_pubic_key($ssh);
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

		private function send_pubic_key($ssh='')
		{
			$id = 1;
			$my_info = $this->mykey_model->get_info($id);
			$ssh->exec("cat >> .ssh/authorized_keys <<EOF
".$my_info->publickey."
EOF
");
			redirect(admin_url('migrate/build_vm_windows'));
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

	/*
///////////////thực chạy API///////
 */
	private function api_createVM_windows($ip='', $node='', $info_VM_windows='', $ssh='')
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

			$form_params_create_VM = array(
				'vmid' => $id_VM,
				'name' => $info_VM_windows[1],
				'ide2' => 'none,media=cdrom',
				'ostype' => 'wxp',
				'ide0' => 'local_data:'.$info_VM_windows[5].',format=raw',
				'sockets' => '1',
				'cores' => $info_VM_windows[2],
				'memory' => $info_VM_windows[3],
				'net0' => 'rtl8139,bridge='.$info_VM_windows[4],
			);

			$response_create_VM = $client->request('POST', 'https://'.$ip.':8006/api2/json/nodes/'.$node.'/qemu', array('headers' => $headers, 'form_params' => $form_params_create_VM, 'verify' => false ));
			echo $response_create_VM->getBody();
			echo $this->cli_convert_image_vmdk_to_raw($ssh, $info_VM_windows[0], $id_VM);
			return 'hoàn thành chuyển động';
		}
	}

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

	}

	/*
	////////////Chạy command trên node////
	 */

	private function cli_convert_image_vmdk_to_raw($ssh='', $path_file_vmdk='', $id_VM='')
	{
		echo $ssh->exec("qemu-img convert -p ".$path_file_vmdk." -O raw /home/block-vcloudstack/local_data/images/".$id_VM."/vm-".$id_VM."-disk-0.raw");
		echo $ssh->exec("qm set ".$id_VM." -delete vmgenid");
		echo "Convert thàng công";
	}
}
?>