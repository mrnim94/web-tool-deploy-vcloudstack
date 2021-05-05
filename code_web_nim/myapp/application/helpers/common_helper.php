<?php
	/*
	hàm dùng đê gọi đường dẫn các file trong thư mục public
	 * url là tham số truyền vào, tên file trong thư mục public
	 */
	function public_url($url='')
	{
		return base_url('public/'.$url);
	}

	/*
	In dữ liệu của mảng
	 */
	function pre($list, $exit= true)
	{
		echo "<pre>";
		print_r($list);
		if ($exit)
		{
			die();
		}
	}

	function convert_vi_to_en($str)
	{
		    $characters = array(
				'/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|�� �|ặ|ẳ|ẵ|ắ/' => 'a',
				'/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
				'/ì|í|ị|ỉ|ĩ/' => 'i',
				'/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/' => 'o',
				'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/' => 'u',
				'/ỳ|ý|ỵ|ỷ|ỹ/' => 'y',
				'/đ/' => 'd',
				'/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|�� �|Ặ|Ẳ|Ẵ/' => 'A',
				'/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
				'/Ì|Í|Ị|Ỉ|Ĩ/' => 'I',
				'/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ợ|Ở|Ớ|Ỡ/' => 'O',
				'/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/' => 'U',
				'/Ỳ|Ý|Ỵ|Ỷ|Ỹ/' => 'Y',
				'/Đ/' => 'D',
			);
			return preg_replace(array_keys($characters), array_values($characters), $str);
	}

		function LocDau($str)
	{
		$str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|�� �|ặ|ẳ|ẵ|ắ)/", 'a', $str);
		$str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
		$str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
		$str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
		$str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
		$str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
		$str = preg_replace("/(đ)/", 'd', $str);
		$str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|�� �|Ặ|Ẳ|Ẵ)/", 'A', $str);
		$str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
		$str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
		$str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ợ|Ở|Ớ|Ỡ)/", 'O', $str);
		$str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
		$str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
		$str = preg_replace("/(Đ)/", 'D', $str);
		$str = preg_replace("/( |'|,|\||\.|\"|\?|\/|\%|–|!)/", '-', $str);
		$str = preg_replace("/(\()/", '-', $str);
		$str = preg_replace("/(\))/", '-', $str);
		$str = preg_replace("/(&)/", '-', $str);
	    $str = preg_replace("/“/", '', $str);
	    $str = preg_replace("/”/", '', $str);
	    $str = preg_replace("/;/", '', $str);
		return strtolower($str);
	}

	//Xóa 1 dòng ký tự trong file
	function deleteLineInFile($file,$string)
	{
		$i=0;$array=array();
		$read = fopen($file, "r") or die("can't open the file");
		while(!feof($read)) {
			$array[$i] = fgets($read);
			++$i;
		}
		fclose($read);
		$write = fopen($file, "w") or die("can't open the file");
		foreach($array as $a) {
			if(!strstr($a,$string)) fwrite($write,$a);
		}
		fclose($write);
	}

	//Đọc nội dung file thay cho file_get_contents
	function ReadContentsFile($link='')
	{
		$ch = curl_init();
		$timeout = 5; // set to zero for no timeout
		curl_setopt ($ch, CURLOPT_URL, $link);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);

		// display file
		return $file_contents;
	}
 ?>