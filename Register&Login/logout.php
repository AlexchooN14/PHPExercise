<?php
    session_start();
    session_destroy();
    header("Location: ../Index/index.php", TRUE, 301);
    exit;
?>