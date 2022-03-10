<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Tech Stack</title>
    <meta charset="UTF-8" />
    <meta name="author" content="Jeremy Webster, Keloth Justyn Dylan, Ben McIntyre, William Mibus" />
    <meta name="description" content="Assignment 2" />
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css"> <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> <!-- Font -->
    <link rel="stylesheet" href="styles/style.css"/>
    <link rel="icon" href="images/favicon.png">
    <script src="scripts/script.js" defer></script>
</head>
<body>
    <?php require_once "inc/menu.inc.php"; ?>
    <div class="banner"></div>
    <div id="content">
        <?php
            require_once "inc/dbconn.inc.php";
            require_once "inc/product.inc.php";

            PopulateList($conn, isset($_GET["category"]) ? $_GET["category"] : null, 40, null);
        ?>
    </div>
    <?php require_once "inc/footer.inc.php"; ?>
</body>
</html>
