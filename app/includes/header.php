<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
        <img src="<?php echo BASE_URL . "/assets/images/logo.png"; ?>" class="header_logo" alt="Logo">
    </a>
    <div class="header_right d-flex">
        <div class="dropdown">
            <i class="bi bi-person-circle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
            <div class="dropdown-menu" aria-labelledby="profileDropdown">
                <a class="dropdown-item" href="#">Profile</a>
                <a class="dropdown-item" href="#">Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">Logout</a>
            </div>
        </div>
    </div>
  </div>
</nav>
