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
            <a class="navbar-brand" href="#pablo">Quản lý Mô Hình 2 Node Vật Lý</a>
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
                  <h4 class="card-title">Cấu hình cho node vật lý Master</h4>
                </div>
                <div class="card-body ">
                  <!-- <form class="form-horizontal"> -->
                    <div class="row">
                      <label class="col-md-3 col-form-label">Tạo mới cluster:</label>
                      <div class="col-md-4">
                        <div class="form-group has-default">
                          <button id="create_new_cluster" class="btn btn-primary">Thực hiện</button>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group has-default">
                          <button id="get_info_cluster" class="btn btn-primary">Lấy thông tin cluster</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Cài đặt các package:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="install_package_one" class="btn btn-primary">Thực hiện</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Phân vùng ổ đĩa và tạo storage:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="chia_o_dia" class="btn btn-primary">Phân vùng và tạo</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Thêm role và user :</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="add_role" class="btn btn-primary">Thêm</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Enable firewall và tạo Secgroup</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="add_firewall_secgroup" class="btn btn-primary">Thực hiện</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Tạo VM Ubuntu:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="create_vm_ubuntu" class="btn btn-primary">Tạo</button>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <label class="col-md-3 col-form-label">Tạo Virtual IP cho Node vật lý:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="create_vip_node" class="btn btn-primary">Tạo</button>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="row">
                      <label class="col-md-3 col-form-label">Password</label>
                      <div class="col-md-9">
                        <div class="form-group">
                          <input type="password" class="form-control">
                        </div>
                      </div>
                    </div> -->
                    <!-- <div class="row">
                      <label class="col-md-3"></label>
                      <div class="col-md-9">
                        <div class="form-check">
                          <label class="form-check-label">
                            <input class="form-check-input" type="checkbox" value=""> Remember me
                            <span class="form-check-sign">
                              <span class="check"></span>
                            </span>
                          </label>
                        </div>
                      </div>
                    </div> -->
                  <!-- </form> -->
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
              <div class="card">
                <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">assignment</i>
                  </div>
                  <h4 class="card-title">List VMs</h4>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="table table_list_vms">
                      <thead>
                        <tr>
                          <th class="text-center">VMID</th>
                          <th>Name</th>
                          <th>Status</th>
                          <th class="text-right">Actions</th>
                        </tr>
                      </thead>
                      <tbody id="list_vms">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <?php //end col-md-12 ?>
        </div>
        <!-- end row -->
    </div>
</div>