<?php
date_default_timezone_set('America/Denver');
require_once "../app/database/connection.php";
require_once "../path.php";
session_start();

$files = glob("../app/functions/*.php");
foreach ($files as $file) {
    require_once $file;
}
logoutUser($conn);
if(isLoggedIn() == false) {
    header('location:' . BASE_URL . '/login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <link rel="stylesheet" href="../assets/css/main.css?v=<?php //echo time(); ?>"> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="../assets/css/styles.css?v=<?php echo time(); ?>">

    <!-- TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/7kainuaawjddfzf3pj7t2fm3qdjgq5smjfjtsw3l4kqfd1h4/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>View Asset | Asset360</title>

    <!-- Styles -->
        <style>
            /* .application-details {
                max-width: 80%;
                padding: 20px;
                margin: 20px auto;
            } */
            .btn-group .dropdown-toggle::after {
                display: none;
            }
            .btn:focus {
                outline: none;
                box-shadow: none;
            }
            .scrollable-table-container {
                overflow-y: auto; 
                height: calc(100vh - 375px);
            }
            .sticky-header th {
              position: sticky;
              top: 0;
              background-color: #fff;
              z-index: 1;
            }

            .sticky-header th::after {
              content: '';
              position: absolute;
              left: 0;
              bottom: 0;
              width: 100%;
              border-bottom: 2px solid rgb(217, 222, 226);
            }
        </style>
    <!-- end Styles -->

</head>
<body>

    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

<div class="container" style="padding: 0 75px 0 75px;">
    <div class="application-details">

            <h2 class="mt-4">
                Event Log
            </h2>
            <hr>



        <div class="scrollable-table-container">
                                <table class="table">
                                  <thead class="sticky-header">
                                    <tr>
                                      <th scope="col">Issue Key</th>
                                      <th scope="col">Asset Tag No</th>
                                      <th scope="col">Issue Type</th>
                                      <th scope="col">Summary</th>
                                      <th scope="col">Created</th>
                                      <th scope="col">Updated</th>
                                      <th scope="col">Link</th>
                                    </tr>
                                  </thead>
                                  <tbody id="jiraTableBody">
                                    <!-- Table rows will be dynamically added here -->
                                  </tbody>
                                </table>
                            </div>

                        <!-- get audit issues script -->
                            <script>
                                // Assuming $off_asset_tag_no contains the current asset tag
                                // var assetTag = "<?php //echo $off_asset_tag_no; ?>";

                                fetch('<?php echo BASE_URL; ?>/api/get_jira_all_data.php')
                                    .then(response => {
                                        if (!response.ok) {
                                            throw new Error('Network response was not ok');
                                        }
                                        return response.json();
                                    })
                                    .then(data => {
                                        // Check if the response contains issues
                                        if (data && data.issues && data.issues.length > 0) {
                                            document.getElementById("jiraTableBody").innerHTML = "";
                                            data.issues.forEach(issue => {
                                                var newRow = document.createElement("tr");
                                                newRow.innerHTML = `<td>${issue.key}</td><td><a href="https://asset360.morganserver.com/asset/view/?id=${issue.fields.labels}" target="_blank" class="" style="">${issue.fields.labels}</a></td><td>${issue.fields.summary}</td><td>${issue.fields.issuetype.name}</td><td>${new Date(issue.fields.created).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td><td>${new Date(issue.fields.updated).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td><td><a href="https://garrett-morgan.atlassian.net/browse/${issue.key}" target="_blank" class="badge text-bg-primary text-decoration-none" style="font-size: 14px;">Visit</a></td>`;
                                                document.getElementById("jiraTableBody").appendChild(newRow);
                                            });
                                        } else {
                                            // Handle case where no issues are found
                                            document.getElementById("jiraTableBody").innerHTML = "<tr><td colspan='4'>No issues found</td></tr>";
                                        }
                                    })
                                    .catch(error => {
                                        console.error('Error:', error);
                                        // Handle other errors, e.g., network issues or server errors
                                        document.getElementById("jiraTableBody").innerHTML = "<tr><td colspan='4'>Error fetching data</td></tr>";
                                    });
                            </script>


                        <!-- end get audit issues script -->





            
    </div>
</div>



<!-- 
<script>
        tinymce.init({
            selector: 'textarea',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script> -->



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
