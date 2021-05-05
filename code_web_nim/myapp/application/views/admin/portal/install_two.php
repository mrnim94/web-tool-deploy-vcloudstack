<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-transparent  navbar-absolute fixed-top">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <div class="navbar-minimize">
                <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                    <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                    <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
                </button>
            </div>
            <a class="navbar-brand" href="#pablo">Quản lý cài VM Portal</a>
        </div>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>

        <?php $this->load->view('admin/navbar', $this->data) ;?>
    </div>
</nav>
<!-- End Navbar -->

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
            <?php $this->load->view('admin/message', $this->data);?>
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">build</i>
                  </div>
                  <h4 class="card-title">Cấu hình cho VM Portal(acitve & backup)</h4>
                </div>
                <div class="card-body ">
                  <!-- <form class="form-horizontal"> -->
                    <div class="row">
                      <label class="col-md-3 col-form-label">Khai báo các thông số cho việc cấu hình 2 portal:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="config_2portal" class="btn btn-primary">Thực hiện</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Thêm chứng chỉ SSL:</label>
                      <div class="col-md-4">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="option_ssl_2portal" value="default" checked>Sử dụng mặc định
                            <span class="circle">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="option_ssl_2portal" value="update">Thêm mới
                            <span class="circle">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group has-default">
                          <button id="add_ssl_2portal" class="btn btn-primary">Thêm SSL</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Cài đặt Portal:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="install_portal_two" class="btn btn-primary">Thực hiện</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Show Log Cài đặt:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="log_install_portal_two" class="btn btn-primary">Thực hiện</button>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="card-footer ">
                  <div class="row">
                    <!-- <div class="col-md-9">
                      <button type="submit" class="btn btn-fill btn-rose">Sign in</button>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card ">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">sd_card</i>
                  </div>
                  <h4 class="card-title">Cấu hình Database</h4>
                </div>
                <div class="card-body ">
                  <form method="post" action="<?php echo admin_url('portal/install_one');?>" class="form-horizontal" enctype="multipart/form-data">
                    <div class="row">
                      <label class="col-md-3 col-form-label">Cơ sở dữ liệu:</label>
                      <div class="col-md-9">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="option_db" value="default" checked> Mặc định
                            <span class="circle">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="radio" name="option_db" value="update"> Update
                            <span class="circle">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">File database (.sql):</label>
                      <div class="col-md-9">
                          <div class="fileinput fileinput-new text-center" data-provides="fileinput">
                              <div class="fileinput-new thumbnail">
                                  <img src="<?php echo base_url('upload/icon-database.png'); ?>" alt="file_database">
                              </div>
                              <div class="fileinput-preview fileinput-exists thumbnail"></div>
                              <div>
                                  <span class="btn btn-rose btn-round btn-file">
                                  <span class="fileinput-new">Select image</span>
                                  <span class="fileinput-exists">Change</span>
                                  <input id="upload_file_sql" class="btn btn-round btn-white" type="file" name="file_database" disabled />
                                  <div class="ripple-container"></div>
                                  </span>
                                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                              </div>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-9">
                        <input type="submit" class="btn btn-fill btn-rose" name="submit_update_csdl" value="Thực Hiện">
                      </div>
                    </div>
                  </form>
                </div>
                <!-- <div class="card-footer ">
                  <div class="row">
                    <div class="col-md-9">
                      <button type="submit" class="btn btn-fill btn-rose">Sign in</button>
                    </div>
                  </div>
                </div> -->
              </div>
            </div>
            <?php //end col-md-12 ?>
        </div>
        <!-- end row -->
    </div>
</div>