<?php

// This (insecure!!!) mockup represents your running system.
// In most cases it does not need to be touched in order to add Almefy
// as most Frameworks offer ways to add custom hooks, plugins or custom
// REST API points.

class Backend {

    // loaded from users.json file
    public $users = [];

    public function __construct() {
        $this->load_users_from_db();
    }

    protected $event_listeners = [
        'after_create_user' => [],
        'after_delete_user' => [],
    ];

    public function add_event_listener($event, $cb) {
        array_push($this->event_listeners[$event], $cb);
    }

    private function email_taken($email) {
        foreach ($this->users as $user) {
            if($user->email === $email) {
                return true;
            }
        }
        return false;
    }

    public function load_users_from_db() {
        $file = file_get_contents(__DIR__ . '/users.json', true);
        $this->users = json_decode($file);
    }

    private function save_users_to_db() {
        $new_contents = json_encode($this->users, JSON_PRETTY_PRINT);
        file_put_contents(__DIR__ . '/users.json', $new_contents);
    }

    public function create_user($username, $email, $password) {

        echo "creating";


        if($email == '' ||  $email == null) {
            throw new Exception("No email provided!");
        }

        if($password == '' ||  $password == null) {
            throw new Exception("No password provided!");
        }

        if($this->email_taken($email)) {
            throw new Exception("Email already taken!");
        }

        // we just assume the email is valid for this example

        $new_user = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
        ];
        $this->users[] = (object)$new_user;

        // call event listeners after creating the user
        foreach ($this->event_listeners['after_create_user'] as $listener) {
            $event = [
                'username' => $username,
                'email' => $email,
            ];
            $listener($event);
        }

        $this->save_users_to_db();
    }

    public function delete_user($email) {
        // if user exist
        // delete
        // TODO: event
        $user = $this->get_user_by_mail($email);
        if(!$user) {
            throw new Exception("User does not exist.");
        }

        // delete the user from the "database"
        $key_to_delete = null;
        foreach ($this->users as $key => $user) {
            if($user->email == $email) {
                // $key_to_delete = $key;
                unset($this->users[$key]);
                break;
            }
        }

        // call event listeners after deleting the user
        foreach ($this->event_listeners['after_delete_user'] as $listener) {
            $event = [
                'username' => $username,
                'email' => $email,
            ];
            $listener($event);
        }

        $this->rem_auth_cookie();
        $this->save_users_to_db();
    }

    public function get_user_by_mail($email) {
        foreach ($this->users as $user) {
            if($user->email == $email) {
                return $user;
            }
        }

        return null;
    }

    public function login($email, $password) {
        $user = $this->get_user_by_mail($email);
    
        if(!$user) {
            throw new Exception("User '$email' does not exist.");
        }

        if($user->password === $password) {
            $this->set_auth_cookie($email);
            return true;
        } else {
            throw new Exception("Could not authenticate.");
        }
    }

    public function set_auth_cookie($email) {
        session_start();
        $_SESSION['authenticated_user'] = $email;
    }
    
    public function rem_auth_cookie() {
        session_start();
        // unset($_SESSION['authenticated_user']);
        session_unset(); 
        session_destroy(); 
    }

    public function is_logged_in() {
        session_start();
        return isset($_SESSION['authenticated_user']);
    }
    
    public function get_current_user() {
        session_start();
        $email = $_SESSION['authenticated_user'];
        $user = $this->get_user_by_mail($email);
        return $user;
    }

    function redirect($url, $code = 302) {
        if (headers_sent() !== true) {
            if (strlen(session_id()) > 0) {
                // if using sessions
                session_regenerate_id(true); // avoids session fixation 	attacks
                session_write_close(); // avoids having sessions lock other requests
            }

            if (strncmp('cgi', PHP_SAPI, 3) === 0) {
                header(sprintf('Status: %03u', $code), true, $code);
            }

            header('Location: ' . $url, true, preg_match('~^30[1237]$~', $code) > 0 ? $code : 302);
        }
        exit();
    }
}