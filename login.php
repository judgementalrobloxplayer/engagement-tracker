<?php
include_once "./loginMain.php";
$errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = $_POST["email"];
        $password = $_POST["password"];
        if (login($email, $password)) {
            storeLoginCookie($email, $password);
            startSession($email);
        }
        if ($_SESSION['admin']) {
            redirect("./admin/");
        } else {
            redirect("./home/");
        }
        exit();
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    } 
} else {
    checkSession("login");
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="./login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-heading">Login</div>
        <div class="error-box" <?php if (empty($errorMsg)) : ?>style="display:none;"<?php endif; ?>>
            <?php if (!empty($errorMsg)) : ?>
                <p class="error-message"><?php echo $errorMsg; ?></p>
            <?php endif; ?>
        </div>
        <form method="post" action="">
            <div class="input-container">
                <input type="text" name="email" placeholder="Email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                <input type="password" name="password" placeholder="Password">
            </div>
            <input class="login-button" type="submit" value="Login">
        </form>
        <a href="./forgot.php" class="forgot-password-link">Forgot password?</a>
        <a href="./signup.php" class="new-account-link">New Account</a >
    </div>
</body>
</html>
