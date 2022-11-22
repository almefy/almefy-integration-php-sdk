<?php

// Load $backend and $almefy objects
require_once __DIR__ . "/../loadbackend.php"; 

$redirect = $_GET['redirect'];

$backend->rem_auth_cookie();
$backend->redirect($redirect . "?message=You have been logged out.");