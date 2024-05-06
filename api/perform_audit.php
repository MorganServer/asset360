<?php

// Function to create a Jira issue
function createJiraIssue($issueDataJson) {
    $jiraApiUrl = 'https://garrett-morgan.atlassian.net/rest/api/3/issue';

    $jiraUsername = "garrett.morgan.pro@gmail.com";
    $one = "ATATT3xFfGF0rALQ3ASzKULCbilrrrykWqEfW8yJlCjhGCHW0mBSQcSaGP";
    $two = "Ewxq8DC39D1ElsXBo7Wp3tHueO26Jp3AZ2IQNmfrq5urdZ91wfhGWB5xWd";
    $three = "gTqWD8qGQ7qbt-CcgAp4WWeSlO0WrN30VB238osKbafBEBHz1WPbUSWeyh";
    $four = "dXhAHA2kA=46A5779A";
    $jiraApiToken = $one . $two . $three . $four;

    $contextOptions = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n" .
                        "Authorization: Basic " . base64_encode($jiraUsername . ':' . $jiraApiToken) . "\r\n",
            'content' => $issueDataJson,
            'ignore_errors' => true 
        )
    );

    $context = stream_context_create($contextOptions);

    $response = file_get_contents($jiraApiUrl, false, $context);

    return $response;
}

// Function to update asset table
function updateAssetTable($asset_id) {
    // Connect to database
    $conn = mysqli_connect("localhost", "dbuser", "DBuser123!", "asset_management");

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Get today's date
    $today = date('Y-m-d');
    
    // Calculate one month from today
    $next_month = date('Y-m-d', strtotime('+1 month', strtotime($today)));
    $next_month_weekday = date('N', strtotime($next_month));

    // If next month starts on a Saturday or Sunday, add days to make it Monday
    if ($next_month_weekday == 6) {
        $next_month = date('Y-m-d', strtotime($next_month . ' +2 days'));
    } elseif ($next_month_weekday == 7) {
        $next_month = date('Y-m-d', strtotime($next_month . ' +1 day'));
    }

    // Update asset table
    $sql = "UPDATE assets SET audit_schedule = '$next_month' WHERE asset_id = $asset_id";

    if (mysqli_query($conn, $sql)) {
        echo "Asset table updated successfully.";
    } else {
        echo "Error updating asset table: " . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}

// Check if request method is POST and if data is received
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['auditIssueData'])) {
    // Retrieve issue data from POST request
    $issueDataJson = $_POST['auditIssueData'];
    
    // Create the issue
    $response = createJiraIssue($issueDataJson);
    
    // Check for errors
    if ($response === false) {
        echo "Error: Unable to create Jira ticket.";
    } else {
        echo $response; // Return Jira API response
        // If Jira issue created successfully, update asset table
        if (isset($_POST['asset_id'])) {
            $asset_id = $_POST['asset_id'];
            updateAssetTable($asset_id);
        }
    }
} else {
    echo "Error: Invalid request.";
}

?>
