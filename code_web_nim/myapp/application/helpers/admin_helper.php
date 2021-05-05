<?php 
	function admin_url($url='')
	{
		//đường này dẫn đến các file trong thư mục controller/admin
		return base_url('admin/'.$url);
	}
 ?>