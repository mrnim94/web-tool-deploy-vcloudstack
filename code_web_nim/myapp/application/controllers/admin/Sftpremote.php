<?php
use phpseclib\Net\SFTP;
use phpseclib\Crypt\RSA;

/**
 *
 */
class Sftpremote extends My_Controller
{
	function __construct()
	{
		parent::__construct();
		//load file Admin_model.php
		$this->load->model('physicalnode_model');
		$this->load->model('mykey_model');
	}

	function download_ubuntu()
	{
		$where = array(
		'incognito' => $this->session->userdata("incognito_node"),
		);
		if ($this->physicalnode_model->check_exists($where))
		{
			$ip = $this->physicalnode_model->get_info_rule($where, $field='ip_node');
			$id = 1;
			$my_info = $this->mykey_model->get_info($id);
			$sftp = new SFTP($ip->ip_node,22);
			$key = new RSA();
			$key->setPassword($my_info->passphrase);
			$key->loadKey($my_info->privatekey);
			if (!$sftp->login('root', $key)) {
			    //exit('Login Failed');
			    http_response_code(503);
			}
			else
			{
				// echo FCPATH;
				//$sftp->put('filename.remote', 'xxx');
				// echo $sftp->pwd() . "\r\n";
				$sftp->setTimeout(1000);
				$sftp->size('/home/nginx/php-nim.mrnim.com/public_html/upload/iso/CentOS-7-x86_64-Minimal-1810.iso');
				$sftp->put("/home/CentOS-7-x86_64-Minimal-1810.iso", "/home/nginx/php-nim.mrnim.com/public_html/upload/iso/CentOS-7-x86_64-Minimal-1810.iso", SFTP::SOURCE_LOCAL_FILE | SFTP::RESUME_START);
			}
		}
		else
		{
			return false;
		}
	}
}
?>