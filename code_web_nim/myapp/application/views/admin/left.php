<div class="sidebar" data-color="rose" data-background-color="black" data-image="<?php echo public_url('admin/') ?>assets/img/sidebar-1.jpg">
<!--
Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

Tip 2: you can also add an image using data-image tag
-->

    <div class="logo">
        <a href="" class="simple-text logo-normal">
     &nbsp;&nbsp;&nbsp;Mr.Nim <i class="material-icons">favorite</i> vcloudstack
        </a>
    </div>

    <div class="sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="<?php echo base_url('upload/logo-vng-cloud-white.png'); ?>" />
            </div>
            <div class="user-info">
                <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span>
               <!-- <?php //echo $this->session->userdata('admin_name'); ?> -->
               hiển thị username
              <b class="caret"></b>
            </span>
                </a>
                <div class="collapse" id="collapseExample">
                    <ul class="nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> MP </span>
                                <span class="sidebar-normal"> My Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> EP </span>
                                <span class="sidebar-normal"> Edit Profile </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <span class="sidebar-mini"> S </span>
                                <span class="sidebar-normal"> Settings </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <ul class="nav">

            <li class="nav-item active ">
                <a class="nav-link" href="<?php echo admin_url('home') ;?>">
                    <i class="material-icons">dashboard</i>
                    <p> Dashboard </p>
                </a>
            </li>

            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#portal">
                    <i class="material-icons">list</i>
                    <p> Cài Đặt Mới Portal
                        <b class="caret"></b>
                    </p>
                </a>

                <div class="collapse" id="portal">
                    <ul class="nav">
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('portal/one') ;?>">
                                <span class="sidebar-mini"> I </span>
                                <span class="sidebar-normal"> Mô Hình 1 Node (Non HA) </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('portal/two') ;?>">
                                <span class="sidebar-mini"> II </span>
                                <span class="sidebar-normal"> Mô Hình 2 Node (HA Portal) </span>
                            </a>
                        </li>
                        <!-- <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('danhmucbaiviet') ;?>">
                                <span class="sidebar-mini"> III </span>
                                <span class="sidebar-normal"> Mô Hình 3 Node (HA System) </span>
                            </a>
                        </li> -->
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#node">
                    <i class="material-icons">commute</i>
                    <p> Cài Đặt Node Vật Lý
                        <b class="caret"></b>
                    </p>
                </a>

                <div class="collapse" id="node">
                    <ul class="nav">
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('node/one') ;?>">
                                <span class="sidebar-mini"> I </span>
                                <span class="sidebar-normal"> Mô Hình 1 Node (Non HA) </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('node/two') ;?>">
                                <span class="sidebar-mini"> II </span>
                                <span class="sidebar-normal"> Mô Hình 2 Node (HA Portal) </span>
                            </a>
                        </li>
                        <!-- <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('danhmucbaiviet') ;?>">
                                <span class="sidebar-mini"> III </span>
                                <span class="sidebar-normal"> Mô Hình 3 Node (HA System) </span>
                            </a>
                        </li> -->
                    </ul>
                </div>
            </li>
            <li class="nav-item ">
                <a class="nav-link" data-toggle="collapse" href="#migrate">
                    <i class="material-icons">extension</i>
                    <p> Build VM Từ Migrate
                        <b class="caret"></b>
                    </p>
                </a>

                <div class="collapse" id="migrate">
                    <ul class="nav">
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('migrate/windows') ;?>">
                                <span class="sidebar-mini"> WD </span>
                                <span class="sidebar-normal"> Build VM windows </span>
                            </a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link" href="<?php echo admin_url('migrate/linux') ;?>">
                                <span class="sidebar-mini"> LN </span>
                                <span class="sidebar-normal"> Build VM linux </span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>