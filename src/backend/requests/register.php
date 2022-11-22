<?php

// Load $backend and $almefy objects
require_once __DIR__ . "/../loadbackend.php"; 

// TODO: handle request inside Backend.php

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

$redirect = $_GET['redirect'];

// echo $username . " " . $email . " " . $redirect;

// TODO: try catch
$backend->create_user($username, $email, $password);

$backend->redirect($redirect . "?message=Account created. Check your mail at $email and scan the code with the almefy app.");
