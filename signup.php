<?php
include_once "./loginMain.php";
$errorMsg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = $_POST["email"];
        emailFromWebpage($email);
        redirect("./success.php?source=signup");
        exit(); 
    } catch (Exception $e) {
        $errorMsg = $e->getMessage();
    } 
}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign-Up</title>
    <link rel="stylesheet" type="text/css" href="./login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-heading">Sign-Up</div>
        <div class="error-box" <?php if (empty($errorMsg)) : ?>style="display:none;"<?php endif; ?>>
            <?php if (!empty($errorMsg)) : ?>
                <p class="error-message"><?php echo $errorMsg; ?></p>
            <?php endif; ?>
        </div>
        <form method="post" action="">
            <div class="input-container">
                <input type="text" name="email" placeholder="Email">
            </div>
            <input class="login-button" type="submit" value="Submit">
        </form>
        <a href="./" class="new-account-link">Back</a>
    </div>
</body>
</html>
