<?php
//////////////khởi tạo cluster//////////////
if($this->uri->segment(3) == 'install_2one_master')
{
?>
	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#create_new_cluster").click(function(){
 				var action = 'create_new_cluster';
 				swal({
                title: 'Tạo mới cluster cho hệ thống',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">Đặt tên cho cluster</label>'+
                  	'<input type="text" id="swal-input0" required="true" class="form-control" placeholder="...........">' +
                  	'</div>'
                  	,
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input0').val(),
                    ])
                  })
                }
                }).then(function (result) {
	                var json_info_name_cluster = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('node/create_cluster'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_name_cluster : json_info_name_cluster,action : action},
	                })
	                .done(function(response){
						if (response ='packages_first_ok')
						{
							swal({
							  position: 'top-end',
							  type: 'success',
							  title: 'Đã tạo xong cluster!',
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
	/////////////Tạo Virtual IP cho node vật lý////////////////
	?>
 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#create_vip_node").click(function(){
 				var action = 'create_vip_node';
 				swal({
                title: 'Khai báo các thông tin cho VIP',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">Nhập IP ảo cho cluster:</label>'+
                  	'<input type="text" id="swal-input0" required="true" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Đặt chỉ số ưu tiên</label>'+
                  	'<input type="number" id="swal-input1" required="true" class="form-control" placeholder="..............nếu bạn đang cấu hình master thì để 100......">' +
                  	'</div>'
                  	,
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input0').val(),
                      $('#swal-input1').val(),
                    ])
                  })
                }
                }).then(function (result) {
	                var json_info_create_vip_node = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('node/create_vip_node'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_create_vip_node : json_info_create_vip_node,action : action},
	                })
	                .done(function(response){
						if (response ='packages_first_ok')
						{
							swal({
							  position: 'top-end',
							  type: 'success',
							  title: 'Đã tạo xong Virtual IP!',
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
?>

<?php
//////////////khởi tạo cluster//////////////
if($this->uri->segment(3) == 'install_2one_slave')
{
?>
	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#add_node_to_cluster").click(function(){
 				var action = 'add_node_to_cluster';
 				swal({
                title: 'Thêm node vào cluster',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">IP của node Master:</label>'+
                  	'<input type="text" id="swal-input0" required="true" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Password root của node Master:</label>'+
                  	'<input type="password" id="swal-input1" required="true" class="form-control" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Fingerprint của node Master:</label><br>'+
                  	'<textarea id="swal-textarea2" class="form-control"></textarea>'+
                  	'</div>'
                  	,
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input0').val(),
                      $('#swal-input1').val(),
                      $('#swal-textarea2').val(),
                    ])
                  })
                }
                }).then(function (result) {
	                var json_info_add_node_to_cluster = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('node/add_node_to_cluster'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_add_node_to_cluster : json_info_add_node_to_cluster,action : action},
	                })
	                .done(function(response){
						if (response ='packages_first_ok')
						{
							swal({
							  position: 'top-end',
							  type: 'success',
							  title: 'Đã add xong cluster!',
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

 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#chia_o_dia_and_update_storage").click(function(){
 				var action = 'chia_o_dia_and_update_storage';
	    		$.ajax({
	                url: "<?php echo admin_url('node/chia_o_dia_and_update_storage'); ?>",
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
<?php
}
?>


<?php
//////////////khởi tạo cluster//////////////
if($this->uri->segment(3) == 'install_2one_slave' || $this->uri->segment(3) == 'install_2one_master')
{
?>
	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#create_vip_node").click(function(){
 				var action = 'create_vip_node';
 				swal({
                title: 'Khai báo các thông tin cho VIP',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">Nhập IP ảo cho cluster:</label>'+
                  	'<input type="text" id="swal-input0" required="true" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Đặt chỉ số ưu tiên</label>'+
                  	'<input type="number" id="swal-input1" required="true" class="form-control" placeholder="..............nếu bạn đang cấu hình master thì để 100......">' +
                  	'</div>'
                  	,
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input0').val(),
                      $('#swal-input1').val(),
                    ])
                  })
                }
                }).then(function (result) {
	                var json_info_create_vip_node = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('node/create_vip_node'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_create_vip_node : json_info_create_vip_node,action : action},
	                })
	                .done(function(response){
						swal({
						  position: 'top-end',
						  type: 'success',
						  title: 'Đã tạo xong Virtual IP!',
						  showConfirmButton: false,
						  timer: 2000
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

 	<script type="text/javascript">
 		$(document).ready(function() {
 			$("#get_info_cluster").click(function(){
 				var action = 'get_info_cluster';
 				$.ajax({
	                url: "<?php echo admin_url('node/get_info_cluster'); ?>",
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
						  text: 'Xin hãy thử lại',
						});
					}
	            })
	            .done(function(response){
					swal({
	                title: 'Lấy thông tin cluster',
	                html:
	                	'<div class="form-group">'+
						'<label text-align="left">Fingerprint::</label><br>'+
	                  	'<textarea class="form-control">'+response+'</textarea>'+
	                  	'</div>'
	                  	,
	                })
	            });
 			});
 		});
 	</script>
<?php
}
?>