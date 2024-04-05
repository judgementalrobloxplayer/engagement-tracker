<?php
include_once 'adminMain.php';
checkSession('admin');
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <div id = "logout" class = button-link>Log Out</div>
        <div class="admin-heading">Admin Home</div>
        <button class="admin-buttons"><a href="adminCreate.php">Create New Event</a></button>
    </div>

    <script>
        document.getElementById("logout").addEventListener("click", function() {
    this.disabled = true;

    const formData = new FormData();
    formData.append('type', 'logout');

    fetch('../php/api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        location.reload();
    })
    .catch(error => {
        this.disabled = false;
        console.error('Error:', error);
    });
});
</script>
</body>
</html>
