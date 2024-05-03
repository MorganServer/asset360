<?php

// Assuming you have a database connection already established

// Fetch notifications from the database
$query = "SELECT * FROM notifications ORDER BY notification_created DESC LIMIT 5"; // Adjust query as needed
$result = mysqli_query($connection, $query);

// Check if query was successful
if ($result) {
    $notifications = array();

    // Fetch each row and add it to the $notifications array
    while ($row = mysqli_fetch_assoc($result)) {
        $notification = array(
            'details' => $row['details'],
            // 'link' => $row['link'],
            // 'icon' => $row['icon']
        );
        $notifications[] = $notification;
    }

    // Return notifications as JSON
    echo json_encode($notifications);
} else {
    // Handle database query error
    $error_message = "Error fetching notifications: " . mysqli_error($connection);
    echo json_encode(array('error' => $error_message));
}

// Close database connection
mysqli_close($connection);

?>
