<?php
include_once './homeMain.php';
checkSession("home");
$points = getPoints();
?>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="./index.css" />
  </head>
  <body>
    <div class="topnav" id="myTopnav">
      <a href="./" class="active">Home</a>
      <a href="./events.php">Events</a>
      <a href="./redeem.php">Redeem</a>
      <a href="./leaderboard.php" >Leaderboard</a>
      <a href="javascript:void(0);" class="right" id="logout">Logout</a>
      <a href="javascript:void(0);" class="icon" onclick="navBar()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <div class="event-container" id="event-container">
      <h1>Welcome, <?php echo $_SESSION["user"]?>!</h1>
      <form class="form-container" id="eventsFormPost">
      </form>
    </div> 
    <div class="container">
        <div class="shaded-box">
            <div id="points" class="points"><?php echo($points); ?></div>
            <p class="small-text">Points</p>
        </div>
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
