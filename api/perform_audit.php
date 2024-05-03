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

// Function to update the audit_schedule field in the assets table
function updateAuditSchedule($assetId) {
    // Check if $assetId is provided
    if (!empty($assetId)) {
        // Sanitize the input to prevent SQL injection
        $safeAssetId = mysqli_real_escape_string($yourDbConnection, $assetId);

        // Update the audit_schedule field for the given asset_id
        $sql = "UPDATE assets SET audit_schedule = NOW() WHERE asset_id = '$safeAssetId'";
        
        // Execute the query
        if (mysqli_query($yourDbConnection, $sql)) {
            echo "Audit schedule updated successfully for asset_id: $safeAssetId";
        } else {
            echo "Error updating audit schedule: " . mysqli_error($yourDbConnection);
        }
    } else {
        echo "Error: Missing asset_id.";
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
        // Check if asset_id is provided
        if (isset($_POST['asset_id'])) {
            // Update audit_schedule for the asset
            updateAuditSchedule($_POST['asset_id']);
        } else {
            echo "Warning: No asset_id provided for updating audit schedule.";
        }

        echo $response; // Return Jira API response
    }
} else {
    echo "Error: Invalid request.";
}
?>