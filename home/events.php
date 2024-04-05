<?php
include_once "homeMain.php";
checkSession("home");
$events = queryEvents();
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
      <a href="./events.php" class="active">Events</a>
      <a href="./redeem.php">Redeem</a>
      <a href="./leaderboard.php">Leaderboard</a>
      <a href="javascript:void(0);" class="right" id="logout">Logout</a>
      <a href="javascript:void(0);" class="icon" onclick="navBar()">
        <i class="fa fa-bars"></i>
      </a>
    </div>
    <div class="event-container" id="event-container">
      <h1>Today's Events</h1>
      <form class="form-container" id="eventsFormPost">
        <?php
          if (count($events) > 0) {
            foreach ($events as $event) {
              echo '<div class="event">';
              echo '<div class="event-details">';
              echo "<p>Event Name: " . $event["eventName"] . "</p>";
              echo "<p>Location: " . $event["field"] . "</p>";
              echo "<p>Sport: " . $event["sport"] . "</p>";
              echo "<p>Start Time: " . $event["start"] . "</p>";
              echo "<p>Points: " . $event["points"] . "</p>";
              echo "</div>";
              echo '<button type="submit" name="'. $event["eventID"] . '" class="check-in-button">Check In</button>';
              echo "</div>";
            }     
          } else {
            echo '<div class="event">';
            echo "<h3> No Events Currently Avaliable For Check-In</h3>";
            echo "</div>";
          }
        ?> 
      </form>
    </div>
    <div id="modal" class="modal" <?php if (!empty($msg)) : ?>style="display:block;" <?php endif; ?>>
      <div class="modal-content">
        <span class="close" id="closeModalBtn">&times;</span>
        <h2 id="modalTitle"></h2>
        <p id="modalContent"></p>
        <button class="successbutton" id="closeModalBtn2" style="text-decoration: none;">Okay</button>
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
    <script>
      const closeModalBtn = document.getElementById("closeModalBtn");
      const closeModalBtn2 = document.getElementById("closeModalBtn2");
      const modal = document.getElementById("modal");
      closeModalBtn.addEventListener("click", () => {
        modal.style.display = "none";
      });
      closeModalBtn2.addEventListener("click", () => {
        modal.style.display = "none";
      });
      window.addEventListener("click", (event) => {
        if (event.target === modal) {
          modal.style.display = "none";
        }
      });
    </script>
    <script>
      const modalTitle = document.getElementById('modalTitle');
      const modalContent = document.getElementById('modalContent');
      document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("eventsFormPost");
        const checkInForm = new FormData(form);

        form.querySelectorAll(".check-in-button").forEach(function (button) {
          button.addEventListener("click", function (e) {
            e.preventDefault();
            checkInForm.set('type', 'checkin');
            const eventId = button.getAttribute("name");
            checkInForm.set('eventId', eventId);

            if ("geolocation" in navigator) {
              navigator.geolocation.getCurrentPosition(function (position) {
                checkInForm.set('lat', position.coords.latitude);
                checkInForm.set('long', position.coords.longitude);

                fetch('../php/api.php', {
                  method: 'POST',
                  body: checkInForm
                })
                  .then(response => {
                    if (!response.ok) {
                      throw new Error('Network response was not ok');
                    }
                    return response.json(); 
                  })
                  .then(data => {
                    if (data.success == 1) {
                      modalTitle.textContent = "Success!";
                      modalContent.textContent = data.error;
                    } else {
                      modalTitle.textContent = "Error";
                      modalContent.textContent = data.error;
                    }
                    
                    modal.style.display = "block";
                  })
                  .catch(error => {
                    console.error('Error:', error);
                  });
              }, function (error) {
                modalTitle.textContent = "Error getting location";
                modalContent.textContent = "";
                modal.style.display = "block";
              });
            } else {
              modalTitle.textContent = "Geolocation is not available in your browser!";
              modalContent.textContent = "";
              modal.style.display = "block";
            }
          });
        });
      });

    </script>
  </body>
</html>
