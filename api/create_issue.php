<?php

// Set up Jira credentials and URL
$jiraUrl = "https://garrett-morgan.atlassian.net/rest/api/3/issue"; // e.g., "https://yourdomain.atlassian.net/rest/api/3/issue"
$username = "garrett.morgan.pro@gmail.com";
$password = "ATATT3xFfGF0xQDSVqyPxXeCS8sBme1cSEeKQiPKzsJYkcOV-Xf_1chFleVeXT31G1bpPm08fOvrBRTOjubsZvV48fVWeBGjkIRwFhA52x5el5w0KqriC0K0g9_mCkc-nm5qPAYFgxAmFJrd6zUfjvnbgr0ifyYpDr3KFYVKsXEGITBSAOyUj_o=613CF7AB"; // For authentication, it's recommended to use an API token instead of your password

// Ticket data
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


// Convert data to JSON format
$data = json_encode($issueData);

// Set up cURL request
$ch = curl_init($jiraUrl);
curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    "Content-Type: application/json",
    "Content-Length: " . strlen($data)
));

// Execute the cURL request
$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if ($httpCode == 201) {
    echo "Ticket created successfully!";
} else {
    echo "Error creating ticket: " . $result;
}

// Close cURL session
curl_close($ch);
?>