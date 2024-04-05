<?php
include_once "./connection.php";
include_once "./main.php";
checkSession("");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['type'])) {
        $response = array();

        if ($_POST['type'] === 'logout') {
            logout();
        } elseif ($_POST['type'] === 'checkin') {
            include_once "../home/homeMain.php";
            try {
                checkLocation($_POST['lat'],$_POST['long'],$_POST['eventId']);
                $response['success'] = 1;
                $response['error'] = "Checked In!";
            } catch (Exception $e) {
                $response['success'] = 0;
                $response['error'] = $e->getMessage();;
            }
            echo json_encode($response);            
        }
        
    } 
}
?>
