<?php

// Include the necessary files
include_once '../../path.php'; // Assuming this defines BASE_URL
include_once ROOT_PATH . '/app/database/connection.php';

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
        // Update asset_schedule field in the database
        $sql = "UPDATE assets SET asset_schedule = DATE_ADD(NOW(), INTERVAL 1 MONTH) WHERE asset_id = <YOUR_ASSET_ID>";
        if (mysqli_query($conn, $sql)) {
            echo $response; // Return Jira API response
        } else {
            echo "Error updating asset_schedule: " . mysqli_error($conn);
        }
    }
} else {
    echo "Error: Invalid request.";
}

?>
