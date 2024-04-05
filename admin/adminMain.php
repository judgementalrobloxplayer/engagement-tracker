<?php
include_once '../php/connection.php';
include_once '../php/main.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eventName = $_POST["eventName"];
    $selectedField = $_POST["selectedField"];
    $selectedSport = $_POST["selectedSport"];
    $startTime = $_POST["startTime"];
    $points = $_POST["points"];
    $eventDate = $_POST["eventDate"];
    $description = $_POST["eventDescriptions"];

    $datetimeString = $eventDate . ' ' . $startTime;    

    $combinedDateTime = new DateTime($datetimeString);
    eventAdd($eventName, $selectedField, $combinedDateTime, $selectedSport, $description, $points);    
}

function eventAdd($eventName, $field, $start, $sport, $description, $points) {
    $pdo = openDB();
    $query = "SELECT MAX(eventID) AS maxID FROM events";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $maxEventID = $result['maxID'] + 1;

    $sql = "SELECT * FROM events";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $numAttended = 0;

    
     $sql = "INSERT INTO events (eventName, field, start, end, sport, description, points, eventID, numAttended) 
            VALUES (:eventName, :field, :start, :end, :sport, :description, :points, :eventID, :numAttended)";
    
    $stmtInsert = $pdo->prepare($sql);
    
    // Bind parameters
    $stmtInsert->bindValue(':eventName', $eventName);
    $stmtInsert->bindValue(':field', $field);
    $stmtInsert->bindValue(':start', $start->format('Y-m-d H:i:s'));
    $stmtInsert->bindValue(':end', $start->modify('+3 hours')->format('Y-m-d H:i:s'));
    $stmtInsert->bindValue(':sport', $sport);
    $stmtInsert->bindValue(':description', $description);
    $stmtInsert->bindValue(':points', $points);
    $stmtInsert->bindValue(':eventID', $maxEventID);
    $stmtInsert->bindValue(':numAttended', $numAttended);
    
    if ($stmtInsert->execute()) {} else {}
    
    $pdo = null;
    echo "Event added successfully!";
}

function queryEvents() {
  $pdo = openDB(); 
  $query = "SELECT * FROM fields ORDER BY fieldName ASC";

  $statement = $pdo->prepare($query);
  $statement->execute();

  $fields = $statement->fetchAll(PDO::FETCH_ASSOC);
  $pdo = null;
  return $fields;
}

?>
