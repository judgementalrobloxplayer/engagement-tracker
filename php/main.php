<?php

function passwordMatch($e, $pw) {
    $pdo = openDB();
    $query = "SELECT COUNT(*) FROM user WHERE email = :email AND pw = :pw";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':email', $e, PDO::PARAM_STR);
    $statement->bindParam(':pw', $pw, PDO::PARAM_STR);
    $statement->execute();

    $count = $statement->fetchColumn();
    $pdo = null;
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

function login($e, $pw) {
    if (validate($e)) {
        if (emailExists($e)) {
            if (passwordMatch($e, $pw)) {
                return true;
            }
            else {
                throw new Exception("Password and email do not match", 3);
            }
        }
        else {
            throw new Exception("Email does not exist", 4);
        }
    }
    else {
        throw new Exception("Email is invalid", 5);
    }
}


function emailExists($email) {
    $pdo = openDB();
    $query = "SELECT COUNT(*) FROM user WHERE email = :searchString";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':searchString', $email, PDO::PARAM_STR);
    $statement->execute();

    $count = $statement->fetchColumn();
    $pdo = null;
    if ($count > 0) {
        return true;
    } else {
        return false;
    }
}

function validate($email) {
    $email = trim($email);

    if (!preg_match('/^[A-Za-z0-9._%+-]+@example\.org$/', $email)) {
        return false;
    }
    else {
        return true;
    }
}


function storeLoginCookie($user, $pass) {
    $expiration = time() + (10 * 365 * 24 * 60 * 60); 
    setcookie("ChickenE", $user, $expiration, '/');
    setcookie("ChickenP", $pass, $expiration, '/');

}

function checkSession($page) {
    if (isset($_COOKIE['ChickenE']) && isset($_COOKIE['ChickenP'])) {
        if (session_id() == '' || !isset($_SESSION) || session_status() === PHP_SESSION_NONE) {
            if (readLoginCookie()) {
                switch ($page) {
                    case "login" :
                        if ($_SESSION['admin']) {
                            redirect("./admin/");
                        } else {
                            redirect("./home/");
                        }
                        break; 
                    case "home" :
                        if ($_SESSION['admin']) {
                            redirect("../admin/");
                        }
                        break; 
                    case "admin" :
                        if (!$_SESSION['admin']) {
                            redirect("../home/");
                        }
                        break; 
                    default:
                        break;
                }

            } else {
                setcookie("ChickenE", "", time() - 3600, '/');
                setcookie("ChickenP", "", time() - 3600, '/');
                if ($page != "login") {
                    redirect("../");
                }
            }
        } 
    } else {
        setcookie("ChickenE", "", time() - 3600, '/');
        setcookie("ChickenP", "", time() - 3600, '/');
        if ($page != "login") {
            redirect("../");
        }
    }
}


function logout() {
    session_unset();
    session_destroy();
    setcookie("ChickenE", "", time() - 3600, '/');
    setcookie("ChickenP", "", time() - 3600, '/');
    redirect("../");
}

function startSession($e) {
    session_start();
    $_SESSION["user"] = $e;
    $pdo = openDB();
    $query = "SELECT admin FROM user WHERE email = :searchString";
    $statement = $pdo->prepare($query);
    $statement->bindParam(':searchString', $e, PDO::PARAM_STR);
    $statement->execute();
    $_SESSION['admin'] = $statement->fetch(PDO::FETCH_ASSOC)['admin'];
    $pdo = null;
    
}


function readLoginCookie() {
    try {
        if (isset($_COOKIE['ChickenE']) && isset($_COOKIE['ChickenP'])) {

            $userCookie = $_COOKIE['ChickenE'];
            $passCookie = $_COOKIE['ChickenP'];
            if (login($userCookie, $passCookie)) {
                startSession($userCookie);
                return true;
            }
        } else {
            setcookie("ChickenE", "", time() - 3600, '/');
            setcookie("ChickenP", "", time() - 3600, '/');
            throw new Exception("Cookie Missing");
        }
    } catch (Exception $e) {
        return false;
    }
}

function redirect($url)
{
    if (!headers_sent())
    {
        header('Location: '.$url);
        exit;
    }
    else
    {
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
    }
}


?>