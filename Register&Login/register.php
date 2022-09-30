<!-- Header file -->
<?php 
    require_once('header.php');
    require_once('../functions.php');
?>

<h1 style="color: aliceblue; margin: 0; padding: 0;">Register now!</h1>

<div class="container form">
        <div class="container register">
            <div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                    <label for="username"></label>
                    <input type="text" name="username" id="username" placeholder="Name" value="<?= htmlspecialchars($_POST['username'] ?? "") ?>">
                    <label for="email"></label>
                    <input type="email" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? "") ?>">
                    <label for="password"></label>
                    <input type="password" name="password" id="password" placeholder="Password">
                    <label for="password-confirm"></label>
                    <input type="password" name="password-confirm" id="password-confirm" placeholder="Confirm Password">
                    <div class="container-buttons">
                        <div><button type="submit" name="submit">Submit</button></div>
                        <div><a href="login.php">Log In</a></div>                        
                    </div>                    
                </form>
            </div>
        </div>
        
    <div class="container photo"></div>
</div>

<?php

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirmed_password = $_POST['password-confirm'];

        if (empty($username)) displayError("Username is required!");
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) displayError("Invalid email!");
        elseif (strlen($password) < 8) displayError("Password must be at least 8 characters!");
        elseif (!preg_match("/[a-z]/i", $password)) displayError("Password must contain at least 1 letter!");
        elseif (!preg_match("/[0-9]/", $password)) displayError("Password must contain at least 1 number!");
        elseif ($password !== $confirmed_password) displayError("Passwords must match!");
        else {
            $activation_code = generateActivationCode();
            echo $activation_code;
            registerUser($username, $email, $password, $activation_code);
        }
        
    }    
?>

<?php
    require_once('footer.php'); 
?>
