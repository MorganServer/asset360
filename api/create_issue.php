<?php

require __DIR__ . '/vendor/autoload.php'; // Include Composer's autoloader

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

error_reporting(E_ALL); 
ini_set('display_errors', 1);

// Function to create a Jira issue
function createJiraIssue($issueDataJson) {
    // Jira API endpoint to create an issue
    $jiraApiUrl = 'https://garrett-morgan.atlassian.net/rest/api/3/issue';

    // Jira username and API token (for authentication)
    $jiraUsername = "garrett.morgan.pro@gmail.com";
    $jiraApiToken = $_ENV['PASSWORD'];

    // Create HTTP context options
    $contextOptions = array(
        'http' => array(
            'method' => 'POST',
            'header' => "Content-Type: application/json\r\n" .
                        "Authorization: Basic " . base64_encode($jiraUsername . ':' . $jiraApiToken) . "\r\n",
            'content' => $issueDataJson,
            'ignore_errors' => true // Set to true to suppress errors from response headers
        )
    );

    // Create stream context
    $context = stream_context_create($contextOptions);

    // Make the API request
    $response = file_get_contents($jiraApiUrl, false, $context);

    return $response;
}

// Issue data (you can customize this according to your needs)
$issueData = array(
    "fields" => array(
        "project" => array(
            "key" => "INFRA"
        ),
        "summary" => "Test issue created via Postman",
        "description" => array(
            "type" => "doc",
            "version" => 1,
            "content" => array(
                array(
                    "type" => "paragraph",
                    "content" => array(
                        array(
                            "type" => "text",
                            "text" => "description"
                        )
                    )
                )
            )
        ),
        "issuetype" => array(
            "id" => "10013"
        )
    )
);

// Encode issue data to JSON
$issueDataJson = json_encode($issueData);

// Create the issue
$response = createJiraIssue($issueDataJson);

// Check for errors
if ($response === false) {
    echo "Error: Unable to create Jira ticket.";
} else {
    echo "Jira API Response: " . $response;
}
