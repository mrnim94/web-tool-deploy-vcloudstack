<?php
	/**
	 *
	 */
	class Danhmucbaiviet extends MY_Controller
	{
		function __construct()
		{
			parent::__construct();
			//load file Admin_model.php
			$this->load->model('danhmucbaiviet_model');
		}

		function index()
		{
			$list = $this->danhmucbaiviet_model->get_list();
			$this->data['list'] = $list;

			$total = $this->danhmucbaiviet_model->get_total();
			$this->data['total'] = $total;

			//lấy ra nội dung của biến message thông báo và hiển thị
			$message = $this->session->flashdata('message');
			$this->data['message'] = $message;

			//gọi file hiển thị
			$this->data['temp'] = 'admin/danhmucbaiviet/index';
			$this->load->view('admin/main', $this->data);
		}

		function add()
		{
			//load ra thư viện validation dữ liệu
			$this->load->library('form_validation');
			$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation
			//Nếu mà có giữ liệu POST lên thì kiểm tra
			if($this->input->post())
			{
				$this->form_validation->set_rules('danhmucbaiviet','Tên danh mục bài viết','required');
				// các tập luật các nhau bằng dấu gạch giữa và phải ghi sát nhau

				//nhập liệu chính sách các yêu cầu
				if($this->form_validation->run())
				{
					// thêm vào cơ sở dữ liệu
					$danhmucbaiviet = $this->input->post('danhmucbaiviet');
					$parent_id = $this->input->post('parent_id');
					$ordernum = $this->input->post('ordernum');
					$home = $this->input->post('home');
					$menu = $this->input->post('menu');
					$status = $this->input->post('status');

					// tạo biến data để lưu dữ liệu
					$data = array(
							'danhmucbaiviet'     => $danhmucbaiviet,
							'parent_id' => $parent_id,
							'ordernum' => $ordernum,
							'home' => $home,
							'menu' => $menu,
							'status' => $status,
							);
					//thêm mới cơ sở dữ liệu bằng function create trong Catalog_model
					if ($this->danhmucbaiviet_model->create($data))
					{
						//tạo ra thông báo khi thêm mới addmin thành công gửi sang view
						$this->session->set_flashdata('message', 'Thêm mới danh mục bài viết thành công');
					}
					else
					{
						//tạo ra thông báo khi thêm mới addmin ko thành công gửi sang view
						$this->session->set_flashdata('message', 'Thêm mới danh mục bài viết không thành công');
					}
					//Chuyển tới trang danh sách danhmuc bài viết
					redirect(admin_url('danhmucbaiviet/'));
				}
			}

			//gửi menu đệ quy danh mục bài viết sang vỉew
			$this->data['menu']  = $this->show_categories();

			//gọi file hiển thị
			$this->data['temp'] = 'admin/danhmucbaiviet/add';
			$this->load->view('admin/main', $this->data);
		}
		/*
		 Hàm chỉnh sửa danh mục bài viêt
		 */
		function edit()
		{
			//load ra thư viện validation dữ liệu
			$this->load->library('form_validation');
			$this->load->helper('form');//load cái này để hiện thị lỗi thường đi kèm với form validation

			//lấy ID của danh mục
			$id = $this->uri->rsegment(3);
			$info = $this->danhmucbaiviet_model->get_info($id);
			if (!$info)
			{
				//tạo ra nội dung thông báo
				$this->session->set_flashdata('message', 'Không tồn tại danh mục này');
				redirect(admin_url('danhmucbaiviet'));
			}

			//chuyển $info sang view để admin sửa nội dung
			$this->data['info'] = $info;

			//lấy thông tin danh mục cha
			$info_parent = $this->danhmucbaiviet_model->get_info($info->parent_id);
			$this->data['info_parent'] = $info_parent;
			if($this->input->post())
			{
				$this->form_validation->set_rules('danhmucbaiviet','Tên danh mục bài viết','required');
				// các tập luật các nhau bằng dấu gạch giữa và phải ghi sát nhau

				//nhập liệu chính sách các yêu cầu
				if($this->form_validation->run())
				{
					// thêm vào cơ sở dữ liệu
					$danhmucbaiviet = $this->input->post('danhmucbaiviet');
					$parent_id = $this->input->post('parent_id');
					$ordernum = $this->input->post('ordernum');
					$home = $this->input->post('home');
					$menu = $this->input->post('menu');
					$status = $this->input->post('status');

					// tạo biến data để lưu dữ liệu
					$data = array(
							'danhmucbaiviet' => $danhmucbaiviet,
							'parent_id' => $parent_id,
							'ordernum' => $ordernum,
							'home' => $home,
							'menu' => $menu,
							'status' => $status,
							);
					//thêm mới cơ sở dữ liệu bằng function create trong Catalog_model
					if ($this->danhmucbaiviet_model->update($id ,$data))
					{
						//tạo ra thông báo khi thêm mới addmin thành công gửi sang view
						$this->session->set_flashdata('message', 'Thêm mới danh mục bài viết thành công');
					}
					else
					{
						//tạo ra thông báo khi thêm mới addmin ko thành công gửi sang view
						$this->session->set_flashdata('message', 'Thêm mới danh mục bài viết không thành công');
					}
					//Chuyển tới trang danh sách danhmuc bài viết
					redirect(admin_url('danhmucbaiviet/'));
				}
			}

			//gửi menu đệ quy danh mục bài viết sang vỉew
			$this->data['menu']  = $this->show_categories();

			//gọi file hiển thị
			$this->data['temp'] = 'admin/danhmucbaiviet/edit';
			$this->load->view('admin/main', $this->data);
		}

		/*
		 Hàm để quy danh mục bài viết
		 */
		function show_categories($parent_id='0', $insert_text='-')
		{
			$input = array();
			$str = "";
			$where = array(
				'parent_id' => $parent_id,
			);
			$input['where'] = $where;
			$input['order'] = array('parent_id','DESC');
			$categories = $this->danhmucbaiviet_model->get_list($input);
			if ($categories)
			{
				foreach ($categories as $category)
				{
					$str .= '<option value="'.$category->id.'">'.$insert_text.$category->danhmucbaiviet.'</option>';
					$str .= $this->show_categories($category->id, $insert_text.'-');
				}
			}
			return $str;
		}
		/*
			Hàm xóa Danh mục bài viết
		 */
		function del()
		{
			$id = intval($this->uri->rsegment('3'));
			//lấy thông tin của Danh mục bài viết
			$info = $this->danhmucbaiviet_model->get_info($id);
			if (!$info)
			{
				$this->session->set_flashdata('message', 'Không tồn tại Danh mục bài viết này');
				redirect(admin_url('danhmucbaiviet'));
			}
			else
			{
				$where = array('parent_id' => $info->id);
				$input['where'] = $where;
				$sub_info = $this->danhmucbaiviet_model->get_list($input);
				$danhmuccon = '';
				if ($sub_info)
				{
					foreach ($sub_info as $sub)
					{
						$danhmuccon .= $sub->danhmucbaiviet.',';
					}
					$this->session->set_flashdata('message', 'Bạn phải xóa các danh mục con: '.$danhmuccon.' trước !');
					redirect(admin_url('danhmucbaiviet'));

				}
				//thực hiện xóa Danh mục bài viết
				$this->danhmucbaiviet_model->delete($id);
				$this->session->set_flashdata('message', 'Danh mục bài viết này đã được xóa khỏi hệ thống');
				redirect(admin_url('danhmucbaiviet'));
			}
		}
	}
 ?>