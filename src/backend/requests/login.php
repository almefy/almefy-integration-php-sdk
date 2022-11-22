<?php

// Load $backend and $almefy objects
require_once __DIR__ . "/../loadbackend.php"; 


$email = $_POST['email'];
$password = $_POST['password'];
$redirect = $_GET['redirect'];

echo "$email $password $redirect";

// try catch
$backend->login($email, $password);

$backend->redirect($redirect);