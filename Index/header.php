<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS only -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="../style/index.css">
    <title>Home</title>
</head>
<body class="bg-light p-0">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-5">
    <a class="navbar-brand" href="index.php">Blog</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">

            <?php if (isset($_SESSION["user_id"])): ?>
                <?php if (str_ends_with($_SERVER['PHP_SELF'], "profile.php")): ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a class="nav-link" href="../Register&Login/logout.php">Log Out</a>
                </li>

            <?php else: ?>
                <li class="nav-item">
                    <a class="nav-link" href="../Register&Login/register.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../Register&Login/login.php">Log In</a>
                </li>
            <?php endif; ?>
            
        </ul>
    </div>
    
</nav>
<div class="container-lg">