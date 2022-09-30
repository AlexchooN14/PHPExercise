<?php
    require_once('../functions.php');

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);
        $activation_code = htmlspecialchars($_GET['activation_code']);
        echo $email;
        echo $activation_code;

        $user = getUserByEmail($email);
        var_dump($user);
        if (!isUserActivated($email) || !isUserCodeExpired($email)) {
            if (password_verify($activation_code, $user['activation_code'])) {
                activateUser($user);
            }
        }
        header("Location: login.php", TRUE, 301);
        exit;
    }
    
?>