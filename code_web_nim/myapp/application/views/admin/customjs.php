<?php
if($this->uri->segment(3) == 'install_one' || $this->uri->segment(3) == 'install_2one_master' || $this->uri->segment(3) == 'install_2one_slave')
{
?>
	<?php
	/**
	 * Thêm các parkages cho node vật lý
	 */
	?>
 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#install_package_one").click(function(){
 				var action = 'install_packages';
 				swal.queue([{
				  title: 'Cài đặt Packages cho node',
				  confirmButtonText: 'Thực hiện',
				  text:
				    'Ấn -Thực hiện- ' +
				    'để Cài đặt Packages cho node',
				  showLoaderOnConfirm: true,
				  preConfirm: function () {
				    return new Promise(function (resolve) {
				      //////////////////chèn vào đây///////
						$.ajax({
			                  url: "<?php echo admin_url('node/install_packages'); ?>",
			                  type: "POST",
			                  cache: false,
			                  data: {action : action},
			            })
			            .done(function(response){
							swal({
							  type: 'success',
							  title: 'Oops...',
							  text: 'Đã thoàn thành cài đặt node',
							});
			            })
			            .fail(function(response){
							if (response.status == 500)
							{
								swal({
								  type: 'error',
								  title: 'Oops...',
								  text: 'Lỗi 500 nè!',
								});
							}
							else
							{
								swal({
								  position: 'top-end',
								  type: 'error',
								  title: 'Lỗi trong quá trình cài đặt cho Node',
								  showConfirmButton: false,
								  timer: 3000
								});
							}
			            });
				      ////////////////////chèn vào đây///////
				    })
				  }
				}]);
 			});
 		});
 	</script>

 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#add_firewall_secgroup").click(function(){
 				var action = 'add_firewall_secgroup';
 				$.ajax({
	                url: "<?php echo admin_url('node/add_firewall_secgroup'); ?>",
	                type: "POST",
	                cache: false,
	                data: {action : action},
	            })
	            .fail(function(response){
					if (response.status == 503)
					{
						swal({
						  position: 'top-end',
						  type: 'error',
						  title: 'Kết nối hoặc login bị lỗi!',
						  showConfirmButton: false,
						  timer: 3000
						});
					}
					else
					{
						swal({
						  type: 'error',
						  title: 'Oops...',
						  text: 'Việc cài đặt bị lỗi',
						});
					}
	            })
	            .done(function(response){
					swal({
					  type: 'success',
					  title: 'Ồ dê.',
					  text: 'Đã enable firewall và tạo Secgroup cho Portal!',
					});
	            });
 			});
 		});
 	</script>

 	<script>
	    setInterval(function(){
	    $('#list_vms').load('<?php echo base_url(); ?>admin/node/view_vm_ubuntu');
	    }, 3000);
    </script>

	<?php
	/**
	 * mở console vm
	 */
	?>
    <script>
    	$(document).ready(function() {
    		$('.table_list_vms tbody').on('click','.console_vm',function(){
    			var currow = $(this).closest('tr');
    			var vmid = $.trim(currow.find('td:eq(0)').text());
    			var action = 'console_vm';
    			$.ajax({
	                url: "<?php echo admin_url('node/console_vm'); ?>",
	                type: "POST",
	                cache: false,
	                data: {action : action},
	            })
	            .fail(function(response){
					if (response.status == 503)
					{
						swal({
						  position: 'top-end',
						  type: 'error',
						  title: 'Kết nối hoặc login bị lỗi!',
						  showConfirmButton: false,
						  timer: 3000
						});
					}
					else
					{
						swal({
						  type: 'error',
						  title: 'Oops...',
						  text: 'Việc cài đặt bị lỗi',
						});
					}
	            })
	            .done(function(response){
					swal({
					  type: 'success',
					  title: 'Ồ dê.',
					  text: 'Đã enable firewall và tạo Secgroup cho Portal!',
					});
	            });
    		});
    	});
    </script>

	<?php
	/**
	 * Tạo VM ubuntu cho việc build Portal
	 */
	?>
 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#create_vm_ubuntu").click(function(){

 				var action = 'create_vm_ubuntu';
	    		swal({
                title: 'Cấu hình cho VM Portal',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Cập nhật thông tin',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">IP cho VM Portal ip=x.x.x.x/y,gw=z.z.z.z</label>'+
                  	'<input type="text" id="swal-input1" required="true" class="form-control" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">DNS cho Portal</label>'+
                  	'<input type="text" id="swal-input2" required="true" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Username để đăng nhập</label>'+
					'<input type="text" id="swal-input3" required="true" class="form-control" placeholder="..........." value="root" disabled>' +
					'</div>'+
					'<div class="form-group">'+
					'<label text-align="left">Password để đăng nhập</label>'+
					'<input type="password" id="swal-input4" required="true" class="form-control" placeholder="...........">'+
					'</div>',
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input1').val(),
                      $('#swal-input2').val(),
                      $('#swal-input3').val(),
                      $('#swal-input4').val(),
                      $('#swal-input5').val(),

                    ])
                  })
                }
                }).then(function (result) {
		            swal.queue([{
					  title: 'Tạo VM Portal',
					  confirmButtonText: 'Tạo',
					  text:
					    'Ấn -Tạo- ' +
					    'để tạo portal',
					  showLoaderOnConfirm: true,
					  preConfirm: function () {
					    return new Promise(function (resolve) {
					      //////////////////chèn vào đây///////
					    	var json_info_vm_ubuntu = JSON.stringify(result);
			                $.ajax({
			                      url: "<?php echo admin_url('node/create_vm_ubuntu'); ?>",
			                      type: "POST",
			                      cache: false,
			                      data: {json_info_vm_ubuntu : json_info_vm_ubuntu,action : action},
			                })
			                .done(function(response){
								if (response ='packages_first_ok')
								{
									swal({
									  position: 'top-end',
									  type: 'success',
									  title: 'Đã tạo xong VM!',
									  showConfirmButton: false,
									  timer: 2000
									});
								}
				            })
				            .fail(function(response){
								if (response.status == 500)
								{
									swal({
									  position: 'top-end',
									  type: 'error',
									  title: 'Lỗi 500 nè!',
									  showConfirmButton: false,
									  timer: 3000
									});
								}
								else
								{
									swal({
									  position: 'top-end',
									  type: 'error',
									  title: 'Lỗi trong quá trình cài đặt cho Node',
									  showConfirmButton: false,
									  timer: 3000
									});
								}
				            });
					      ////////////////////chèn vào đây///////
					    })
					  }
					}]);
		            /////////////////////
                }).catch(swal.noop)
 			});
 		});
 	</script>

	<?php
		/**
		 * js cho việc tạo chia phân vùng và tạo storage
		 */
	?>
 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#chia_o_dia").click(function(){
 				var action = 'chia_o_dia';
 				$.ajax({
	                url: "<?php echo admin_url('node/chia_o_dia'); ?>",
	                type: "POST",
	                cache: false,
	                data: {action : action},
	            })
	            .fail(function(response){
					if (response.status == 503)
					{
						swal({
						  position: 'top-end',
						  type: 'error',
						  title: 'Kết nối hoặc login bị lỗi!',
						  showConfirmButton: false,
						  timer: 3000
						});
					}
					else
					{
						swal({
						  type: 'error',
						  title: 'Oops...',
						  text: 'Việc cài đặt bị lỗi',
						});
					}
	            })
	            .done(function(response){
					swal({
					  type: 'success',
					  title: 'OK',
					  text: response,
					});
	            });
 			});
 		});
 	</script>
	<?php #add them passwork cho superadmin,user,admin và add thêm role, pool?>
 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#add_role").click(function(){
 				var action = 'add_role';
 				swal({
                title: 'Thiết lập user cho tài khoản, pool',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">Nhập mật khẩu cho tài khoản superadmin</label>'+
                  	'<input type="password" id="swal-input1" required="true" minlength="8" class="form-control" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập Họ của superadmin:</label>'+
                  	'<input type="text" id="swal-input2" required="true" class="form-control" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập Tên của superadmin:</label>'+
                  	'<input type="text" id="swal-input3" required="true" class="form-control" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập Email của superadmin:</label>'+
                  	'<input type="email" id="swal-input4" required="true" class="form-control" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập tên công ty hay dự án</label>'+
                  	'<input type="text" id="swal-input5" required="true" class="form-control" placeholder="...........">' +
                  	'</div>',
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input1').val(),
                      $('#swal-input2').val(),
                      $('#swal-input3').val(),
                      $('#swal-input4').val(),
                      $('#swal-input5').val(),
                    ])
                  })
                }
                }).then(function (result) {
	                var json_info_user_pool = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('node/add_role'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_user_pool : json_info_user_pool,action : action},
	                })
	                .done(function(response){
						if (response ='packages_first_ok')
						{
							swal({
							  position: 'top-end',
							  type: 'success',
							  title: 'Đã tạo xong role và user',
							  showConfirmButton: false,
							  timer: 2000
							});
						}
		            })
		            .fail(function(response){
						if (response.status == 500)
						{
							swal({
							  position: 'top-end',
							  type: 'error',
							  title: 'Lỗi 500 nè!',
							  showConfirmButton: false,
							  timer: 3000
							});
						}
						else
						{
							swal({
							  position: 'top-end',
							  type: 'error',
							  title: 'Lỗi trong quá trình cài đặt cho Node',
							  showConfirmButton: false,
							  timer: 3000
							});
						}
		            });
                }).catch(swal.noop)
 			});
 		});
 	</script>
<?php
}

///////////thực hiện JS cho việc migrate
if ($this->uri->segment(3) == 'build_vm_windows')
{
?>
<?php
/*
/////thực hiện kiểm tra đã có images chưa và convert image
 */
?>
	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#checkandconvert_image").click(function(){
 				var action = 'build_vm_windows';
 				swal({
	                title: 'Thực hiện convert image vmdk',
	                showCancelButton: true,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: 'Tạo',
	                cancelButtonText: 'Bỏ qua',
	                html:
	                	'<div class="form-group">'+
						'<label text-align="left">Nhập đường dẫn đến file vmdk trên node vật lý:</label>'+
	                  	'<input type="text" id="swal-input0" required="true" class="form-control" placeholder="...........">' +
	                  	'</div>'+
	                  	'<div class="form-group">'+
						'<label text-align="left">Đặt tên cho VM:</label>'+
	                  	'<input type="text" id="swal-input1" required="true" class="form-control" minlength="8" placeholder="...........">' +
	                  	'</div>'+
	                  	'<div class="form-group">'+
						'<label text-align="left">Số VCPU của VM:</label>'+
	                  	'<input type="number" id="swal-input2" required="true" class="form-control" placeholder="...........">' +
	                  	'</div>'+
	                  	'<div class="form-group">'+
						'<label text-align="left">Dung lượng RAM (MB) của VM:</label>'+
	                  	'<input type="number" id="swal-input3" required="true" class="form-control" placeholder=".........MB">' +
	                  	'</div>'+
	                  	'<div class="form-group">'+
						'<label text-align="left">VM sử dụng card bridge:</label>'+
	                  	'<input type="text" id="swal-input4" required="true" class="form-control" placeholder=".........">' +
	                  	'</div>'+
	                  	'<div class="form-group">'+
						'<label text-align="left">Nhập dung lượng ổ đĩa boot của VM:</label>'+
	                  	'<input type="text" id="swal-input5" required="true" class="form-control" placeholder="...........GB">' +
	                  	'</div>'
	                  	,
	                focusConfirm: false,
	                preConfirm: function () {
	                  return new Promise(function (resolve) {
	                    resolve([
	                      $('#swal-input0').val(),
	                      $('#swal-input1').val(),
	                      $('#swal-input2').val(),
	                      $('#swal-input3').val(),
	                      $('#swal-input4').val(),
	                      $('#swal-input5').val(),
	                    ])
	                  })
	                }
                }).then(function (result) {
	                var json_info_link_image_windows = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('migrate/checkandconvert_image_windows'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_link_image_windows : json_info_link_image_windows,action : action},
	                })
	                .done(function(response){
						swal({
						  type: 'success',
						  title: 'Oops...',
						  text: 'convert image OK',
						});
		            })
		            .fail(function(response){
						if (response.status == 500)
						{
							swal({
							  position: 'top-end',
							  type: 'error',
							  title: 'Lỗi 500 nè!',
							  showConfirmButton: false,
							  timer: 3000
							});
						}
						else if (response.status == 404)
						{
							swal({
							  type: 'error',
							  title: 'Oops...',
							  text: 'File không tồn tại nhé!',
							});
						}
						else if (response.status == 401)
						{
							swal({
							  type: 'error',
							  title: 'Oops...',
							  text: 'Đăng nhập lại node vật ký nhé!',
							});
						}
						else
						{
							swal({
							  position: 'top-end',
							  type: 'error',
							  title: 'Lỗi trong quá trình cài đặt cho Node',
							  showConfirmButton: false,
							  timer: 3000
							});
						}
		            });
                }).catch(swal.noop)
 			});
 		});
	</script>
<?php
}
?>