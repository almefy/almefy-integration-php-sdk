<?php

// Load $backend and $almefy objects
require_once __DIR__ . "/../loadbackend.php"; 

if(!$backend->is_logged_in()) {
    $backend->redirect('/?is_error&message=You must be logged in to delete your account.');
}

$user = $backend->get_current_user();
$backend->delete_user($user->email);

$backend->redirect("/?message=Account deleted.");
