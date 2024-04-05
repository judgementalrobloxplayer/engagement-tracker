<?php
include_once "homeMain.php";
checkSession("home");
$leaderboard = getLeaderboard();
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
      <a href="./redeem.php">Redeem</a>
      <a href="./leaderboard.php" class="active">Leaderboard</a>
      <a href="javascript:void(0);" class="right" id="logout">Logout</a>
      <a href="javascript:void(0);" class="icon" onclick="navBar()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <div class="event-container" id="event-container">
      <h1>Leaderboard</h1>
      <table class="table-container">
          <?php
            $rank = 1;
            if (count($leaderboard) > 0) {
              echo('<tr>');
              echo('<th>Rank</th>');
              echo('<th>Name</th>');
              echo('<th>Score</th>');
              echo('</tr>');
              foreach ($leaderboard as $user) {
                $emailParts = explode('@', $user["email"]);
                $username = $emailParts[0];
                preg_match('/^([a-zA-Z]+)(\d+)$/', $username, $matches);
                if (count($matches) == 3) {
                    $name = $matches[1]; 
                    $year = $matches[2]; 
                    $year = "'" . $year;
                    $output = strtoupper(substr($name, -1)) . '. ' . strtoupper(substr($name, 0, 1)) . substr($name, 1, -1) . ' ' . $year;
                    echo '<tr>';
                    echo '<td>' . $rank .'</td>';
                    $rank += 1;
                    echo "<th>" . $output . "</th>";
                    echo "<th>" . $user["totalPoints"] . "</th>";
                    echo "</tr>";
                }
              }    
            } else {
              echo '<div class="event";>';
              echo "<h3> No Data</h3>";
              echo "</div>";
            }
          ?> 
      </table>
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
