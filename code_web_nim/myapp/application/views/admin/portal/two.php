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
            <a class="navbar-brand" href="#pablo">Quản lý VM Portal</a>
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
            <div class="col-md-12">
                <?php $this->load->view('admin/message', $this->data); ?>
                <div class="card ">
                    <div class="card-header card-header-rose card-header-text">
                        <div class="card-text">
                            <h4 class="card-title"><i class="material-icons">subject</i>&nbsp;Nhập thông tin 2 VM dùng để cài đặt Portal</h4>
                        </div>
                    </div>

                    <div class="card-body ">

                        <form method="POST" action="<?php echo admin_url('portal/two') ;?>" class="form-horizontal">
                            <div class="row">
                                <label class="col-sm-2 col-form-label">IP của VM portal Master:</label>

                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <input type="text" name="ip_portal_master" required="true" class="form-control" placeholder="Điền địa chỉ IP của Node mà bạn muốn cấu hình" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" value="<?php echo set_value('ip_node')?>">
                                        <strong style=" color: red;"><?php echo form_error('ip_node');?></strong>
                                        <!-- <span class="bmd-help">Nhớ chọn danh mục cha cho danh mục mà bạn vừa chọn</span> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">IP của VM portal Backup:</label>

                                <div class="col-sm-10">
                                    <div class="form-group">
                                        <input type="text" name="ip_portal_backup" required="true" class="form-control" placeholder="Điền địa chỉ IP của Node mà bạn muốn cấu hình" minlength="7" maxlength="15" size="15" pattern="^((\d{1,2}|1\d\d|2[0-4]\d|25[0-5])\.){3}(\d{1,2}|1\d\d|2[0-4]\d|25[0-5])$" value="<?php echo set_value('ip_node')?>">
                                        <strong style=" color: red;"><?php echo form_error('ip_node');?></strong>
                                        <!-- <span class="bmd-help">Nhớ chọn danh mục cha cho danh mục mà bạn vừa chọn</span> -->
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer col-md-3 ml-auto mr-auto">
                                <button type="submit" class="btn btn-rose">Gửi</button>
                            </div>
                        </form>

                    </div>

                </div>

            </div>
            <?php //end col-md-12 ?>
        </div>
        <!-- end row -->
    </div>
</div>