<?php

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Jira API endpoint to create an issue
$jiraApiUrl = 'https://garrett-morgan.atlassian.net/rest/api/3/issue';

// Jira username and API token (for authentication)
$jiraUsername = "garrett.morgan.pro@gmail.com";
$jiraApiToken = $_ENV['PASSWORD'];

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

// Check for errors
if ($response === false) {
    echo "Error: Unable to create Jira ticket.";
} else {
    echo "Jira API Response: " . $response;
}