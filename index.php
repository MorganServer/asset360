<?php
require_once "path.php";
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom Styles -->
    <link rel="stylesheet" href="assets/css/styles.css?v=<?php echo time(); ?>">

    <title>Home | Asset360</title>
</head>
<body>
    <?php include(ROOT_PATH . "/app/includes/header.php"); ?>
    <?php include(ROOT_PATH . "/app/includes/sidebar.php"); ?>

    <!-- Main Content -->

        <div class="container" style="">

            <div class="mt-4"></div>

            <div class="card-container">
                <div class="card" style="">
                    <div class="card-body text-center">
                        <p class="card-text">
                            <div class="card_text_left float-start" style="font-size: 45px;">
                                <i class="bi bi-hdd-stack ps-2"></i>
                            </div>
                            <div class="card_text_right float-end">
                                <p class="text-end">9</p>
                                <p class="text-muted text-end">Servers</p>
                            </div>
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Location</h5>
                        <p class="card-text"><?php //echo $location; ?></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Job Type</h5>
                        <p class="card-text"><?php //echo $job_type; ?></p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Job Type</h5>
                        <p class="card-text"><?php //echo $job_type; ?></p>
                    </div>
                </div>
            </div>

        </div>

    <!-- end Main Content -->


    <!-- Bootstrap Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- end Bootstrap Scripts -->
</body>
</html>