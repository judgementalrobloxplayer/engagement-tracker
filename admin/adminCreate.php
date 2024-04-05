<?php
include_once 'adminMain.php';
checkSession('admin');
$fields = queryEvents();
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Event</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <div class="admin-container">
        <a href="index.php" class="button-link">Back</a>
        <div class="admin-heading">Create New Event</div>
        
        <label for="event-name">Event Name:</label>
        <input type="text" id="event-name" class="admin-input" placeholder="Enter event name">
        
        <label for="event-descriptions" style="display:none;">Event Descriptions:</label>
        <textarea id="event-descriptions" class="admin-input" placeholder="Enter event descriptions" rows="4" cols="50" style="display:none;"></textarea>

        <label for="fields">Select a Field:</label>
        <select id="fields" class="admin-dropdown">
            <?php
                foreach ($fields as $field) {
                    echo '<option value="' . $field['fieldID'] . '">' . $field['fieldName'] .'</option>';
                }     
            ?>
        </select>

        <label for="sports">Select a Sport:</label>
        <select id="sports" class="admin-dropdown">
            <option value="soccer">Soccer</option>
            <option value="basketball">Basketball</option>
        </select>

        <label for="start-time">Start Time:</label>
        <input type="time" id="start-time" class="admin-input" />

        <label for="points">Points:</label>
        <input type="number" id="points" class="admin-input" placeholder="Enter points">

        <label for="event-date">Event Date:</label>
        <input type="date" id="event-date" class="admin-input" />
        <div id="error-message" class="error-message"></div>

        <button class="admin-buttons" onclick="openModal()"><a>Confirm</a></button>
    </div>

    <div id="myModal" class="modal">
        <div class="modal-content">
            <div id="modal-info"></div>
            <button class="admin-buttons" onclick="closeModal()"><a>Edit</a></button>
            <button class="admin-buttons" onclick="submitForm()"><a>Submit</a></button>
        </div>
    </div>

    <div id="eventCreatedModal" class="modal">
    <div class="modal-content">
        <h2>Event Created</h2>
        <p>Your event has been successfully created.</p>
        <button class="admin-buttons" onclick="closeEventCreatedModal()"><a>Back to Home</a></button>
    </div>
</div>

    <script>
         function openEventCreatedModal() {
            var eventCreatedModal = document.getElementById("eventCreatedModal");
            eventCreatedModal.style.display = "block";
         }
         function closeEventCreatedModal() {
            var eventCreatedModal = document.getElementById("eventCreatedModal");
            eventCreatedModal.style.display = "none";
            window.location.href = "index.php";
        }
        function openModal() {
            if (validateForm()) {
                var modal = document.getElementById("myModal");
                modal.style.display = "block";
                document.getElementById("modal-info").innerHTML = getFormInformation();
                clearErrorMessage();
            } else {
                displayErrorMessage("Please fill out all the required fields.");
            }
        }

        function validateForm() {
            var inputs = ["event-name", "fields", "sports", "start-time", "points", "event-date"];
            for (var input of inputs) {
                if (input === "points") {
                    var pointsValue = document.getElementById("points").value;
                    if (isNaN(pointsValue) || pointsValue.trim() === "") {
                        displayErrorMessage("Points must be a valid number.");
                        return false;
                    }
                } else if (document.getElementById(input).value === "") {
                    return false;
                }
            }
            return true;
        }


        function displayErrorMessage(message) {
            var errorMessageElement = document.getElementById("error-message");
            errorMessageElement.innerHTML = message;
            errorMessageElement.style.cssText = "background-color: #ffcccc; border: 1px solid #ff0000; display: block; color: #ff0000; padding: 10px;";
        }

        function clearErrorMessage() {
            document.getElementById("error-message").style.display = "none";
        }

        function closeModal() {
            document.getElementById("myModal").style.display = "none";
        }

        async function submitForm() {
            var eventName = document.getElementById("event-name").value;
            var eventDescriptions = document.getElementById("event-descriptions").value;
            var selectedField = document.getElementById("fields").value;
            var selectedSport = document.getElementById("sports").options[document.getElementById("sports").selectedIndex].text;
            var startTime = document.getElementById("start-time").value;
            var points = document.getElementById("points").value;
            var eventDate = document.getElementById("event-date").value;

            var formData = new FormData();
            formData.append('eventName', eventName);
            formData.append('eventDescriptions', eventDescriptions);
            formData.append('selectedField', selectedField);
            formData.append('selectedSport', selectedSport);
            formData.append('startTime', startTime);
            formData.append('points', points);
            formData.append('eventDate', eventDate);

            try {
                const response = await fetch('adminMain.php', {
                    method: 'POST',
                    body: formData
                });
                if (response.ok) {
                    const result = await response.text();
                } else {
                    console.error('Failed to submit the form');
                }
            } catch (error) {
                console.error('An error occurred:', error);
            }

            closeModal();
            openEventCreatedModal();
        }

        function getFormInformation() {
            var eventName = document.getElementById("event-name").value;
            var eventDescriptions = document.getElementById("event-descriptions").value;
            var selectedField = document.getElementById("fields").value;
            var selectedSport = document.getElementById("sports").options[document.getElementById("sports").selectedIndex].text;
            var startTime = document.getElementById("start-time").value;
            var points = document.getElementById("points").value;
            var eventDate = document.getElementById("event-date").value;
            return `<h2>Event Information:</h2>
                    <p><strong>Event Name:</strong> ${eventName}</p>
                    <p><strong>Event Descriptions:</strong> ${eventDescriptions}</p>
                    <p><strong>Selected Field:</strong> ${selectedField}</p>
                    <p><strong>Selected Sport:</strong> ${selectedSport}</p>
                    <p><strong>Start Time:</strong> ${startTime}</p>
                    <p><strong>Points:</strong> ${points}</p>
                    <p><strong>Event Date:</strong> ${eventDate}</p>`;
        }
    </script>
</body>
</html>
