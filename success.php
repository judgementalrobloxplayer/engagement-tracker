<html lang="en"> <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.2.0"></script>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Success</title>
    <link rel="stylesheet" type="text/css" href="./login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-heading" style="padding-bottom: 0px;">Success</div>
        <p id="success-message" style="padding-bottom: 15px; font-size:20px;"></p>
        <a href="./" class="login-button" style="text-decoration: none;">Back To Home</a>
    </div>

    <script>
        function getQueryParam(param) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(param);
       }

    const source = getQueryParam('source');

    if (source === 'signup') {
        document.getElementById('success-message').innerHTML = 'Registration was successful <br> Check Your Email For A New Password';
        triggerConfetti();
    } else if (source === 'forgot') {
        document.getElementById('success-message').innerHTML = 'Password reset was successful <br> Check Your Email For A New Password';
        triggerConfetti();
    } else {
        window.location.href = './';
    }

    function triggerConfetti() {
        const confettiSettings = { target: 'confetti' };
        const confetti = new ConfettiGenerator(confettiSettings);
        confetti.render();
    }
</script>
</body>
</html>
