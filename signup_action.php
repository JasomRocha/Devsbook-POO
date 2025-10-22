<?php
require 'config.php';
require 'models/Auth.php';
require 'debugHelper.php';

$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate'); // 00/00/0000


if($name && $email && $password && $birthdate) {

    $auth = new Auth($pdo, $base);
    $birthday = explode('/', $birthdate);
    if(count($birthday) != 3) {
        $_SESSION['flash'] = 'Data de nascimento inválida';
        header("Location: ".$base."/login.php");
        exit;
    }
    $birthday = $birthday[2].'-'.$birthday[1].'-'.$birthday[0];
    if(strtotime($birthday) === false) {
        $_SESSION['flash'] = 'Data de nascimento inválida';
        header("Location: ".$base."/login.php");
        exit;
    }
    if($auth->emailExists($email) === false) {
        $auth->registerUser($name, $email, $password, $birthday);
        header("Location: ".$base);
        exit;
    } else {
        $_SESSION['flash'] = 'E-mail já cadastrado';
        header("Location: ".$base."/login.php");
        exit;
    }
}

$_SESSION['flash'] = 'Email e/ou senha incorretos.';
header("Location: ".$base."/login.php");
exit;


