<?php
if ($this->uri->segment(2) == 'portal')
{
	if($this->uri->segment(3) == 'install_two')
	{
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#config_2portal").click(function(){
				var action = 'config_2portal';
				swal({
                title: 'Khai báo các thông tin cho Portal',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left">Nhập IP thật hoặc ảo của hệ thống ảo hóa:</label>'+
                  	'<input type="text" id="swal-input0" required="true" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập tên card mạng của VM Master:</label>'+
                  	'<input type="text" id="swal-input1" required="true" class="form-control" placeholder=".....eth0/ens01/enp01......" value="eth0">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập tên card mạng của VM Backup:</label>'+
                  	'<input type="text" id="swal-input2" required="true" class="form-control" placeholder=".....eth0/ens01/enp01......" value="eth0">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Nhập IP ảo dành cho portal:</label>'+
                  	'<input type="text" id="swal-input3" required="true" class="form-control" minlength="7" maxlength="15" size="15" pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" placeholder="...........">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Version Image Api:</label>'+
                  	'<input type="text" id="swal-input4" required="true" class="form-control" placeholder=".....76bfe74-stg......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Version Image vStorage:</label>'+
                  	'<input type="text" id="swal-input5" required="true" class="form-control" placeholder=".....1798cd87-stg......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Version Image Web:</label>'+
                  	'<input type="text" id="swal-input6" required="true" class="form-control" placeholder=".....9b6c6d9-stg......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Domain name sử dụng cho Portal:</label>'+
                  	'<input type="text" id="swal-input7" required="true" class="form-control" placeholder=".....portal-vcloudstack.mrnim.com......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Hotline support:</label>'+
                  	'<input type="number" id="swal-input8" required="true" class="form-control" placeholder=".....090696969......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Skype support:</label>'+
                  	'<input type="text" id="swal-input9" required="true" class="form-control" placeholder=".....suport.skype69......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Mail support:</label>'+
                  	'<input type="email" id="swal-input10" required="true" class="form-control" placeholder=".....suport@vng.com.vn......">' +
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">Full-name của người support:</label>'+
                  	'<input type="text" id="swal-input11" required="true" class="form-control" placeholder=".....Mr.Nim......">' +
                  	'</div>'
                  	,
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-input0').val(),//ip_virtual_server
                      $('#swal-input1').val(),//eth_master_nim
                      $('#swal-input2').val(),//eth_backup_nim
                      $('#swal-input3').val(),//vip_master_nim
                      $('#swal-input4').val(),//sme_api_nim
                      $('#swal-input5').val(),//sme_vstorage_nim
                      $('#swal-input6').val(),//portal_sme_nim
                      $('#swal-input7').val(),//server_name_portal_nim
                      $('#swal-input8').val(),//hotline_support_nim
                      $('#swal-input9').val(),//skype_support_nim
                      $('#swal-input10').val(),//email_support_nim
                      $('#swal-input11').val(),//full_name_nim
                    ])
                  })
                }
                }).then(function (result) {
	                var json_info_config_2portal = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('portal/config_2portal'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_info_config_2portal : json_info_config_2portal,action : action},
	                })
	                .done(function(response){
						swal({
						  type: 'success',
						  title: 'Yeah...',
						  text: response,
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
                }).catch(swal.noop)
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#install_portal_two").click(function(){
				swal.queue([{
				  title: 'Cài đặt Portal(master-backup)',
				  confirmButtonText: 'Thực hiện',
				  text:
				    'Ấn -Thực hiện- ' +
				    'dể portal được tiến hành cài đặt',
				  showLoaderOnConfirm: true,
				  preConfirm: function () {
				    return new Promise(function (resolve) {
				      //////////////////chèn vào đây///////
				    	var action = 'install_portal_two';
						$.ajax({
			                  url: "<?php echo admin_url('portal/install_portal_two'); ?>",
			                  type: "POST",
			                  cache: false,
			                  data: {action : action},
			            })
			            .done(function(response){
							swal({
							  type: 'success',
							  title: 'Oops...',
							  text: 'Đã thoàn thành cài đặt portal',
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
				//////////////////////ket thuc swal.queue///////////
			});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#log_install_portal_two").click(function(){
				var action = 'log_install_portal_two';
				$.ajax({
	                  url: "<?php echo admin_url('portal/log_install_portal_two'); ?>",
	                  type: "POST",
	                  cache: false,
	                  data: {action : action},
	            })
	            .done(function(response){
					swal({
	                title: 'Lấy thông tin log cài đặt',
	                html:
	                	'<div class="form-group">'+
						'<label text-align="left">Log:</label><br>'+
	                  	'<textarea class="form-control" rows="10" style="font-size: 8pt">'+response+'</textarea>'+
	                  	'</div>'
	                  	,
	                })
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
			});
		});
	</script>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#add_ssl_2portal').hide();
		  	$('input[name="option_ssl_2portal"').click(function(){
		    if ($(this).is(':checked'))
			    {
			    	if ($(this).val() == 'update')
			    	{
			    		$('#add_ssl_2portal').show(500);
			    	}
			    	else
			    	{
			    		$('#add_ssl_2portal').hide(500);
			    	}
			    }
		  	});
		});
	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$("#add_ssl_2portal").click(function(){
				var action = 'add_ssl_2portal';
 				swal({
                title: 'Thêm chứng chỉ SSL cho web',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tạo',
                cancelButtonText: 'Bỏ qua',
                html:
                	'<div class="form-group">'+
					'<label text-align="left" class="btn btn-link btn-pinterest">Kiểm tra CERTIFICATE và RSA PRIVATE KEY:</label><br>'+
                  	'<a href="https://www.sslshopper.com/certificate-key-matcher.html" target="_blank" title="Check SSL" class="btn btn-warning" rel="follow, index"><i class="material-icons">warning</i>sslshopper</a>'+
                  	'</div><br>'+
                	'<div class="form-group">'+
					'<label text-align="left">CERTIFICATE:</label><br>'+
                  	'<textarea id="swal-textarea1" class="form-control"></textarea>'+
                  	'</div>'+
                  	'<div class="form-group">'+
					'<label text-align="left">RSA PRIVATE KEY:</label><br>'+
                  	'<textarea id="swal-textarea2" class="form-control"></textarea>'+
                  	'</div>'
                  	,
                focusConfirm: false,
                preConfirm: function () {
                  return new Promise(function (resolve) {
                    resolve([
                      $('#swal-textarea1').val(),
                      $('#swal-textarea2').val(),
                    ])
                  })
                }
                }).then(function (result) {
	                var json_add_ssl = JSON.stringify(result);
	                $.ajax({
	                      url: "<?php echo admin_url('portal/add_ssl'); ?>",
	                      type: "POST",
	                      cache: false,
	                      data: {json_add_ssl : json_add_ssl,action : action},
	                })
	                .done(function(response){
						swal({
						  position: 'top-end',
						  type: 'success',
						  title: 'Đã cập nhật SSL cho việc cài đặt portal !',
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
							  title: 'Lỗi trong quá trình cài đặt cho Portal',
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
}
?>