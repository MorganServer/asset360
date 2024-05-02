<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>/">
        <img src="<?php echo BASE_URL . "/assets/images/logo-white.png"; ?>" class="header_logo" alt="Logo">
    </a>
    <div class="header_right">
      <div class="dropdown d-flex notify_dropdown">
          <div class="position-relative">
              <i class="bi bi-bell-fill" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              </i>
              <!-- Display badge with notification count here -->
              <?php
                  // Count the number of notifications that are not acknowledged
                  $unread_notification_sql = "SELECT COUNT(*) AS unread_count FROM notifications WHERE acknowledged = 0";
                  $unread_notification_result = mysqli_query($conn, $unread_notification_sql);
                  if ($unread_notification_result && mysqli_num_rows($unread_notification_result) > 0) {
                      $unread_count_row = mysqli_fetch_assoc($unread_notification_result);
                      $unread_count = $unread_count_row['unread_count'];
                      // Display badge only if there are unread notifications
                      if ($unread_count > 0) {
                          echo '<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; margin-top: 30%;">' . $unread_count . '</span>';
                      }
                  }
              ?>
          </div>
          <div class="dropdown-menu" aria-labelledby="notificationDropdown" id="notificationMenu" style="width: 500px; padding: 15px; border-radius: 10px !important;">
              <ul class="list-group list-group-flush">
                  <!-- Notifications will be dynamically added here -->
                  <?php
                      $notify_sql = "SELECT * FROM notifications WHERE acknowledged = 0 ORDER BY notification_created DESC LIMIT 5";
                      $notify_result = mysqli_query($conn, $notify_sql);
                      if($notify_result) {
                          $notify_num_rows = mysqli_num_rows($notify_result);
                          if($notify_num_rows > 0) {
                              while ($notify_row = mysqli_fetch_assoc($notify_result)) {
                                  $created = $notify_row['notification_created'];
                                  $notify_created = !empty($created) ? date_format(date_create($created), 'M d, Y') : '-';
                  ?>
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="d-flex flex-column">
                          <?php echo $notify_row['details']; ?>
                          <span class="text-secondary" style="font-size: 12px;"><?php echo $notify_created; ?></span>
                      </div>
                      <form action="" method="POST">
                          <!-- Hidden input field to send notification ID -->
                          <input type="hidden" name="notification_id" value="<?php echo $notify_row['notification_id']; ?>">
                          <!-- Acknowledge button -->
                          <button type="submit" name="acknowledge" class="btn btn-primary btn-sm">Acknowledge</button>
                      </form>
                  </li>
                  <?php
                              }
                          } else {
                              echo '<li class="list-group-item">No new notifications</li>';
                          }
                      } else {
                          echo '<li class="list-group-item">Error fetching notifications</li>';
                      }
                  ?>
              </ul>
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

<!-- <script>
    $(document).ready(function() {
        // Function to fetch notifications from the server
        function fetchNotifications() {
            $.ajax({
                url: '<?php //echo BASE_URL; ?>/api/fetch_notifications.php', // Replace with your server-side script URL
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
</script> -->
