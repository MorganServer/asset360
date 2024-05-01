<?php
// Function to get Jira issues
function getAllJiraIssues($url) {
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
            'ignore_errors' => true 
        )
    );

    $context = stream_context_create($contextOptions);

    $response = file_get_contents($url, false, $context);

    return $response;
}

// Check if request method is GET and if asset tag is received
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Assuming $off_asset_tag_no contains the current asset tag
    // $assetTag = $_GET['asset_tag'];

    // Construct the JQL query string dynamically
    $jqlQuery = "project in (SG, INFRA) AND issueType in (10029,10030)";
    $fields = "summary, issuetype"; // Define the fields you want to retrieve

    // Construct the URL for the Jira API endpoint
    $url = "https://garrett-morgan.atlassian.net/rest/api/3/search?jql=" . urlencode($jqlQuery) . "&fields=" . urlencode($fields);

    // Get Jira issues
    $response = getAllJiraIssues($url);

    // Check if the request was successful
    if ($response !== false) {
        // Return the response data to the client
        echo $response;
    } else {
        // Handle the error
        echo json_encode(["error" => "Failed to fetch data from Jira API", "url" => "Endpoint: " . $url]);
    }
} else {
    echo "Error: Invalid request.";
}
?>
