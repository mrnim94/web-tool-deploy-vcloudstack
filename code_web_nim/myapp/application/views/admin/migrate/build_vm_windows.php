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
            <a class="navbar-brand" href="#pablo">Thực hiện Build VM windows </a>
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
                  <h4 class="card-title">Cấu hình cho node vật lý</h4>
                </div>
                <div class="card-body ">
                  <!-- <form class="form-horizontal"> -->
                    <div class="row">
                      <label class="col-md-3 col-form-label">Kiểm tra và convert image vmdk:</label>
                      <div class="col-md-9">
                        <div class="form-group has-default">
                          <button id="checkandconvert_image" class="btn btn-primary">Thực hiện</button>
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
            <?php //end col-md-12 ?>
        </div>
        <!-- end row -->
    </div>
</div>