<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 17 Jun 2018 18:59:06 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<!-- /Added by HTTrack -->

<head>
    <?php $this->load->view('admin/head') ;?>
</head>

<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NKDMSK6" height="0" width="0" style="display:none;visibility:hidden"></iframe>
    </noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="wrapper">
    <?php
        //load left
        $this->load->view('admin/left');
     ?>

        <div class="main-panel">
            <?php // phần nội dung trang web ?>
            <?php $this->load->view($temp, $this->data);?>
            <?php // END phần nội dung trang web ?>

            <?php
            //load footer trang web
            $this->load->view('admin/footer');
             ?>
        </div> <?php // END class="main-panel" ?>
    </div> <?php // END class="wrapper" ?>
            <?php
            //load widget của trang web
            $this->load->view('admin/widget');
             ?>
<?php 
    //load js và foot cho trang web
    $this->load->view('admin/foot');
 ?>
</body>

<!-- Mirrored from demos.creative-tim.com/material-dashboard-pro/examples/dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Sun, 17 Jun 2018 18:59:06 GMT -->

</html>