<?php
/**
 *
 */
class MY_Controller extends CI_Controller
	{
		//Biến gửi dữ liệu sang view
		public $data = array();

		function __construct()
		{
			//kế thừa từ CI_Controller
			parent::__construct();
			date_default_timezone_set('Asia/Ho_Chi_Minh');
			$this->load->helper('admin');
		}
	}
?>