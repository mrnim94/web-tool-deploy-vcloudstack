<?php
	/**
	 *
	 */
	class Home extends MY_Controller
	{
		function index()
		{
			//trỏ đến mảng data chú ý là sẽ không có dấu $ thì đã trỏ $this
			$this->data['temp'] = 'admin/home/index';
			$this->load->view('admin/main', $this->data);
		}
	}
 ?>