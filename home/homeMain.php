<?php
include_once '../php/connection.php';
include_once '../php/main.php';

function getPoints() {
  $userEmail = $_SESSION["user"];
        
  $pdo = openDB();

  $sql = $pdo->prepare("SELECT points FROM user WHERE email = :email");
  $sql->bindParam(':email', $userEmail, PDO::PARAM_STR);
  $sql->execute();
  $pdo = null;
  return $sql->fetch(PDO::FETCH_ASSOC)['points'];
}

function queryEvents() {
  $pdo = openDB(); 
  $currentTimestamp = gmdate('Y-m-d H:i:s');
  $futureTimestamp = gmdate('Y-m-d'). ' 23:59:59';
  $query = "SELECT * FROM events WHERE start <= :futureTimestamp AND end >= :currentTimestamp ORDER BY start ASC";

  $statement = $pdo->prepare($query);
  $statement->bindParam(':currentTimestamp', $currentTimestamp, PDO::PARAM_STR);
  $statement->bindParam(':futureTimestamp', $futureTimestamp, PDO::PARAM_STR);
  $statement->execute();

  $events = $statement->fetchAll(PDO::FETCH_ASSOC);

  foreach ($events as &$row) {
      $fieldID = $row['field'];

      $query = $pdo->prepare("SELECT * FROM fields where fieldID = :fieldID");
      $query->bindParam(':fieldID', $fieldID);
      $query->execute();

      $result = $query->fetch(PDO::FETCH_ASSOC);

      if ($result) {
          $fieldName = $result['fieldName'];
          $row['field'] = $fieldName;
      }
  }
  $pdo = null;
  return $events;
}

function checkIfAttended($e, $eventID) {
  $pdo = openDB();
  $sql = "SELECT * FROM attended WHERE eventID = :eventID AND email = :email";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
  $stmt->bindParam(':email', $e, PDO::PARAM_STR);
  $stmt->execute();
  $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($data) == 0) {
    $pdo = null;
    return true;
  }
  $pdo = null;
  return false;
}

function addPoints($eventID) {
  $e = $_SESSION["user"];
  if (checkIfAttended($e, $eventID)) {
    $pdo = openDB();
    $sql = $pdo->prepare("SELECT totalPoints from user WHERE email = :email");
    $sql->bindParam(':email', $e, PDO::PARAM_STR);
    $sql->execute();
    
    $pts = getPoints();
    $totalPts = intval($sql->fetch(PDO::FETCH_ASSOC)['totalPoints']);
    
    $stmt = $pdo->prepare("SELECT et.points FROM events as et WHERE et.eventID = $eventID");
    $stmt->execute();
    
    $pts += intval($stmt->fetch(PDO::FETCH_ASSOC)['points']);
    $totalPts += intval($stmt->fetch(PDO::FETCH_ASSOC)['points']);
    
    $stmt = $pdo->prepare("UPDATE user SET points = $pts WHERE email = :email");
    $stmt->bindParam(':email', $e, PDO::PARAM_STR);
    $stmt->execute();
    
    $sql = $pdo->prepare("UPDATE user SET totalPoints = $totalPts WHERE email = :email");
    $sql->bindParam(':email', $e, PDO::PARAM_STR);
    $sql->execute();

    $stmt = $pdo->prepare("INSERT INTO attended (eventID, email) VALUES (:eventID, :email)");
    $stmt->bindParam(':eventID', $eventID, PDO::PARAM_INT);
    $stmt->bindParam(':email', $e, PDO::PARAM_STR);
    $stmt->execute();    
    $pdo = null;
  }
  else {
    throw new Exception("Already checked in.");
  }
}

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000) {
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
}


function checkLocation($Ulat, $Ulong, $eventID) {
  $pdo = openDB();
  $query = "SELECT et.field, ft.latitude, ft.longitude FROM events AS et JOIN fields AS ft ON et.field = ft.fieldID WHERE et.eventID = $eventID";
  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($result) {
      $lat = $result['latitude'];
      $long = $result['longitude'];
  } else {
      throw new Exception("Event not found or associated field does not exist.");
  }
  $pdo = null;
  $dist = haversineGreatCircleDistance($Ulat, $Ulong, $lat, $long);
  if ($dist < 1000) {
      addPoints($eventID);
  } else {
      throw new Exception("Not In Range");
  }
  return true;
}

function getLeaderboard() {
  $pdo = openDB();
  
  $sql = $pdo->prepare("SELECT email, totalPoints FROM user ORDER BY totalPoints DESC LIMIT 15");
  $sql->execute();
  $leaderboard = $sql->fetchAll(PDO::FETCH_ASSOC);
  $pdo = null;
  return $leaderboard;
}

?>
