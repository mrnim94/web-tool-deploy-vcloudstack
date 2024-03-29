<div class="collapse navbar-collapse justify-content-end">
    <form class="navbar-form">
        <div class="input-group no-border">
            <input type="text" value="" class="form-control" placeholder="Search...">
            <button type="submit" class="btn btn-white btn-round btn-just-icon">
                <i class="material-icons">search</i>
                <div class="ripple-container"></div>
            </button>
        </div>
    </form>

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="#pablo">
                <i class="material-icons">dashboard</i>
                <p>
                    <span class="d-lg-none d-md-block">Stats</span>
                </p>
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="https://creative-tim.com/" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">notifications</i>
                <span class="notification">5</span>
                <p>
                    <span class="d-lg-none d-md-block">Some Actions<b class="caret"></b></span>
                </p>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="#">Mike John responded to your email</a>
                <a class="dropdown-item" href="#">You have 5 new tasks</a>
                <a class="dropdown-item" href="#">You're now friend with Andrew</a>
                <a class="dropdown-item" href="#">Another Notification</a>
                <a class="dropdown-item" href="#">Another One</a>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="material-icons">person</i>
                <p>
                    <span class="d-lg-none d-md-block">Some Actions<b class="caret"></b></span>
                </p>
            </a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" href="<?php echo admin_url('admin/logout') ?>"><i class="material-icons">power_off</i>&nbsp;Đăng xuất</a>
            </div>
        </li>
    </ul>
</div>