<?php

// Include the necessary files
include_once '../path.php'; // Assuming this defines BASE_URL
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

// Function to update the audit_schedule field in the assets table
function updateAuditSchedule($conn, $assetId) {
    $nextMonth = date('Y-m-d', strtotime('+1 month'));
    $sql = "UPDATE assets SET audit_schedule = '$nextMonth' WHERE asset_id = $assetId";
    if (mysqli_query($conn, $sql)) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit; // Ensure script stops execution after redirecting
    } else {
        $error[] = 'Error: ' . mysqli_error($conn);
    }
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

        // Assuming you have an asset ID available, you can call the update function here
        $assetId = 123; // Replace 123 with the actual asset ID
        updateAuditSchedule($conn, $assetId);
    }
} else {
    echo "Error: Invalid request.";
}
?>