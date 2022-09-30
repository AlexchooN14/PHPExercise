<!-- Header file -->
<?php 
    require_once('header.php');
    require_once("../functions.php");
    require_once("../Config/app.php");
?>
<!-- Google reCAPTCHA CDN -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<h1 style="color: aliceblue; margin: 0;">Log In!</h1>
<div class="container form">
        <div class="container register">
            <div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                <label for="email"></label>
                <input type="email" name="email" id="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? "") ?>">
                <label for="password"></label>
                <input type="password" name="password" id="password" placeholder="Password">
                <div class="g-recaptcha" style="margin-top: 10px;"
                    data-sitekey="<?php echo RECAPTCHA_SITE_KEY?>">
                </div>
                <div class="container-buttons">
                    <div><button type="submit" name="submit">Submit</button></div>
                    <div><a href="register.php">Register now!</a></div>                        
                </div>
            </form>
            </div>
        </div>
        
    <div class="container photo"></div>
</div>

<?php
    if ($_SERVER['QUERY_STRING']) {
        if (explode("=", $_SERVER['QUERY_STRING'])[1] == "success") {
            echo displayError("Your profile has been created! Check email for verification!", true);
        }
    } 
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $email = $_POST['email'];
        if (!userExists($email)) {
            echo displayError("Invalid login!");
            exit;
        }
        $password = $_POST['password'];
        $user = getUserByEmail($email);          
        $recaptcha = $_POST['g-recaptcha-response'];
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
                . RECAPTCHA_SECRET_KEY . '&response=' . $recaptcha;
        
        $response = json_decode(file_get_contents($url));
        if ($response->success == false) {
            echo displayError("reCAPTCHA Verification Failed!");
        } else {
            if (!isUserActivated($email)) {
                if (!isUserCodeExpired($email)) {
                    echo displayError("Verify your email before login!");
                } else {
                    $activation_code = generateActivationCode();
                    setNewActivationCode($email, $activation_code);
                    sendVerificationEmail($email, $activation_code);
                    echo displayError("Your profile has expired. A new verification email has been sent!");
                }
            } else {
                loginUser($email, $password);
            }
        }
    }
    
?>

<?php require_once('footer.php'); ?>
