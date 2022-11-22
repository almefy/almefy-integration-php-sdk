<?php

// Load $backend and $almefy objects
require_once __DIR__ . "/../loadbackend.php"; 

$email = $_POST['email'];
$redirect = $_GET['redirect'];

if(!$email || $email == '') {
    $backend->redirect($redirect . "?message='No email provided.&is_error'");
    exit;
}

// Set to true, if you want this double down as "register without a password" sign up.
$register_new_addresses = false;

$almefy->send_connect_device_email($email, $register_new_addresses);
$backend->redirect($redirect . "?message=Email sent to $email.");