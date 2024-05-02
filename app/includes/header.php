<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/">
        <img src="<?php echo BASE_URL . "/assets/images/logo-white.png"; ?>" class="header_logo" alt="Logo">
    </a>
    <div class="header_right">
      <div class="dropdown d-flex dropdown">
        <i class="bi bi-bell-fill" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
        <div class="dropdown-menu" aria-labelledby="notificationDropdown">
          <a class="dropdown-item" href="<?php echo BASE_URL; ?>/add-asset.php"><i class="bi bi-pc-display"></i> Add Asset</a>
        </div>
      </div>
      <div class="dropdown d-flex dropdown">
        <i class="bi bi-plus-circle-fill" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
        <div class="dropdown-menu" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="<?php echo BASE_URL; ?>/add-asset.php"><i class="bi bi-pc-display"></i> Add Asset</a>
          <a class="dropdown-item" href="<?php echo BASE_URL; ?>/add-ip.php"><i class="bi bi-geo-fill"></i> Add IP Address</a>
          <!-- <a class="dropdown-item" href="index.php?logout=1"><i class="bi bi-box-arrow-right"></i> Logout</a> -->
        </div>
      </div>

      <div class="dropdown d-flex dropdown">
        <i class="bi bi-person-circle" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
        <div class="dropdown-menu" aria-labelledby="profileDropdown">
          <a class="dropdown-item" href="<?php echo BASE_URL; ?>/pages/my_account.php"><i class="bi bi-person-fill"></i> My Account</a>
          <a class="dropdown-item" href="<?php echo BASE_URL . "/?logout=1" ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </div>
      </div>
    </div>

    
  </div>
</nav>
