<?php


// Function to create a Jira issue
function getJiraIssue() {
    $jiraApiUrl = 'https://garrett-morgan.atlassian.net/rest/api/3/issue';


    $jiraUsername = "garrett.morgan.pro@gmail.com";
    $one = "ATATT3xFfGF0rALQ3ASzKULCbilrrrykWqEfW8yJlCjhGCHW0mBSQcSaGP";
    $two = "Ewxq8DC39D1ElsXBo7Wp3tHueO26Jp3AZ2IQNmfrq5urdZ91wfhGWB5xWd";
    $three = "gTqWD8qGQ7qbt-CcgAp4WWeSlO0WrN30VB238osKbafBEBHz1WPbUSWeyh";
    $four = "dXhAHA2kA=46A5779A";
    $jiraApiToken = $one . $two . $three . $four;

    $contextOptions = array(
        'http' => array(
            'method' => 'GET',
            'header' => "Content-Type: application/json\r\n" .
                        "Authorization: Basic " . base64_encode($jiraUsername . ':' . $jiraApiToken) . "\r\n",
            // 'content' => $issueDataJson,
            'ignore_errors' => true 
        )
    );


    $context = stream_context_create($contextOptions);


    $response = file_get_contents($jiraApiUrl, false, $context);

    return $response;
}


// Assuming $off_asset_tag_no contains the current asset tag
$assetTag = $_GET['asset_tag']; // Assuming the asset tag is passed as a query parameter

// Construct the JQL query string dynamically
$jqlQuery = "project=SG+AND+summary~'" . $assetTag . "'";
$fields = "summary"; // Define the fields you want to retrieve

// Construct the URL for the Jira API endpoint
$url = "https://garrett-morgan.atlassian.net/rest/api/3/search?jql=" . $jqlQuery . "&fields=" . $fields;

// Make a request to the Jira API endpoint
$response = file_get_contents($url);

// Check if the request was successful
if ($response === false) {
    // Handle the error
    echo json_encode(["error" => "Failed to fetch data from Jira API", "url" => "Endpoint: " . $url]);
} else {
    // Return the response data to the client
    
    $response = getJiraIssue();
    echo $response;
}
?>
