<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/">
        <img src="<?php echo BASE_URL . "/assets/images/logo-white.png"; ?>" class="header_logo" alt="Logo">
    </a>
    <div class="header_right">
      <div class="dropdown d-flex">
        <i class="bi bi-bell-fill" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
        <div class="dropdown-menu" aria-labelledby="notificationDropdown" id="notificationMenu">
          <!-- Notifications will be dynamically added here -->
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

<script>
    $(document).ready(function() {
        // Function to fetch notifications from the server
        function fetchNotifications() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>/api/fetch_notifications.php', // Replace with your server-side script URL
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response && response.length > 0) {
                        // Clear existing notifications
                        $('#notificationMenu').empty();
                        
                        // Add fetched notifications to the dropdown
                        response.forEach(function(notification) {
                            $('#notificationMenu').append('<a class="dropdown-item" href="' + notification.link + '"><i class="' + notification.icon + '"></i>' + notification.message + '</a>');
                        });
                    } else {
                        // Handle case where no notifications are returned
                        $('#notificationMenu').html('<a class="dropdown-item" href="#">No notifications</a>');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle AJAX error
                    console.error('Error fetching notifications:', error);
                }
            });
        }
        
        // Call fetchNotifications initially
        fetchNotifications();
        
        // Refresh notifications every 30 seconds (adjust as needed)
        setInterval(fetchNotifications, 30000);
    });
</script>
