<?php

// Load $backend and $almefy objects
require_once __DIR__ . "/../loadbackend.php"; 


// Disable cache headers
// TODO: this needs thorough testing. It works fine on wordpress with nocache_headers()
// https://stackoverflow.com/questions/13640109/how-to-prevent-browser-cache-for-php-site
// TODO: Write description. This is a workaround for in app authentication...
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Get the JWT from header.
// This part is might be hardest as some web servers hide additional headers
// from php. If getallheaders() does not return the almefy header it's probably a configuration 
// missing on your web server.

if(!isset(getallheaders()['X-Almefy-Auth'])) {
    echo "Almefy authentication header missing.";
    // Use your frameworks method to send a proper error message.
    die();
}
$jwt = getallheaders()['X-Almefy-Auth'];

$user = null;
try {
    // Decode the web token
    $token = $almefy->client->decodeJwt($jwt);

    // Check if the user exists on your system
    $user = $backend->get_user_by_mail($token->getIdentifier());

    // (Optional) If the user does not exists, but an Almefy identity was created outside of your system,
    // consider creating the user at this point. 
    // But make sure to delete identities, when deleting user on your system. Otherwise they would be able to re-enter the system
    // with Almefy!

    // Run any other checks you might need to verify a user is allowed to sign in. 
    // if (!$user->isEnabled() || !$user->isSubscriptionActive()) {
    //     return false;
    // }
    
    if (!$almefy->client->verifyToken($token)) {
        // Authentication attempt could not be verified or is invalid
        $backend->redirect('/?is_error&message='.$th->getMessage());
        exit;
    }

    // Login attempt was valid, authenticate the user.
    $backend->set_auth_cookie($user->email);

    // You could also pass a ?redirect= parameter to the auth-controller, or retrieve a setting
    // from a database, to redirect your users to a specific page login.
    $backend->redirect('/');

} catch (\Throwable $th) {
    $backend->redirect('/?is_error&message='.$th->getMessage());
    exit;
}




