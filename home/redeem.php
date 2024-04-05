<?php
include_once "homeMain.php";
checkSession("home");
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="./user.css" />
  </head>
  <body>
    <div class="topnav" id="myTopnav">
      <a href="./">Home</a>
      <a href="./events.php">Events</a>
      <a href="./redeem.php" class="active">Redeem</a>
      <a href="./leaderboard.php">Leaderboard</a>
      <a href="javascript:void(0);" class="right" id="logout">Logout</a>
      <a href="javascript:void(0);" class="icon" onclick="navBar()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <div class="event-container" id="event-container">
      <h1>Redeem Coming Soon</h1>
    </div>
    <script>
      function navBar() {
        var x = document.getElementById("myTopnav");
        if (x.className === "topnav") {
          x.className += " responsive";
        } else {
          x.className = "topnav";
        }
      }
    </script>
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
