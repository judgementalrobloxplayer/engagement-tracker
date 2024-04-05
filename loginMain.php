<?php
include_once './php/emailPHP.php';
include_once './php/connection.php';
include_once './php/main.php';

function generateRandomCode() {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < 6; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

function addDB($email) {
    $pdo = openDB();
    $pw = generateRandomCode();
    $statement = $pdo->prepare("INSERT INTO user (email, pw) VALUES (:email, :pw)");
    
    $statement->bindParam(':email', strtolower($email));
    $statement->bindParam(':pw', $pw);
    $statement->execute();

    sendEmail($email, $pw);
    $pdo = null;
    
}

function emailFromWebpage($e) {
    if (validate($e)) {
        try {
            addDB($e);
            
        } catch (PDOException $err) {
            throw new Exception("Email Already In Database", 2);
        }
    }
    else {
        throw new Exception("Invalid Email Error", 1);
    }
}



function forgetPassword($email) {
    if (validate($email)) {
        if (emailExists($email)) {
            $pdo = openDB();
            $pw = generateRandomCode();
            $statement = $pdo->prepare("UPDATE user SET pw = :pw WHERE email = :email");
            
            $statement->bindParam(':email',strtolower($email));
            $statement->bindParam(':pw', $pw);
            $statement->execute();
            
            sendEmail($email, $pw);
            $pdo = null;
        }
        else {
            throw new Exception("Email does not exist", 4);
        }
    }
    else {
        throw new Exception("Email is invalid", 5);
    }
}


?>
