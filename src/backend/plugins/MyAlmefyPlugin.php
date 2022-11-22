<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

class MyAlmefyPlugin {

    private $backend;

    public $almefy_client = null;

    public function __construct($backend) {
        $this->backend = $backend;
        $this->init_client();
        $this->add_create_user_listener();
        $this->add_delete_user_listener();
    }

    // Initialize the almefy client with a valid key pair.
    private function init_client() {
        try {
            // Only checks if key pair is formatted correctly.
            // The third parameter specifying the Almefy endpoint is optional.
            $this->client = new \Almefy\Client($_ENV['ALMEFY_KEY'], $_ENV['ALMEFY_SECRET'], $_ENV['ALMEFY_API']);
            
            // Actually contacts Almefy Server to find out if the key is valid or not.
            $this->client->check();
        } catch (\Almefy\Exception\InvalidArgumentException $e) {
            echo 'Could not initiate Almefy client: '.$e->getMessage();
            // If the key pair is invalid, you might want to send an email to an administrator.
        }
    }

    // Once we detect that an user has been created, we tell almefy to create
    // an identity associated by an unique identifier, like an email or username.
    private function add_create_user_listener() {
        $this->backend->add_event_listener('after_create_user', function($event) {
            $username = $event['username'];
            $email = $event['email'];

            // You may use any unique string a identifier. 
            // We are using the email because it's the most convenient unique identifier 
            // that's usually available. 
            $identifier = $email;

            // Hint:
            // If you can not or do not want to disable regular password based login,
            // Consider setting the user password to some random string (https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php/31284266#31284266)
            // to make sure they did not use an insecure, guessable password.
            // Users should not use passwords and Almefy at the same time, 
            // as the password could be used to bypass Almefy. 

            echo "sent mail to $identifier";
            try {
                $this->client->enrollIdentity($identifier, array(
                    // Tell Almefy to send the "Connect Device" email (Both options not required).
                    // In almefy_connect_device.php you can see how to connect a new device by showing the connection QR code on a profile page.
                    // You could use the same method used in almefy_connect_device.php to send custom email template including the QR code.
                    'sendEmail' => true,
                    'sendEmailTo' => $email,
                ));

            } catch (\Almefy\Exception\TransportException $e) {
                // Show an error to the user.
                // Consider notifying an administrator about this failure.
                echo 'Could not connect to Almefy service: '.$e->getMessage();
            }
        });
    }

    // If an user account is being deleted, we can safely delete the Almefy identity without impacting the users
    // ability to log into the service.
    private function add_delete_user_listener() {
        $this->backend->add_event_listener('after_delete_user', function($event) {

            $username = $event['username'];
            $email = $event['email'];

            $identifier = $email;

            try {
                $this->client->deleteIdentity($identifier);
            } catch (\Almefy\Exception\TransportException $e) {
                // Show an error to the user.
                // You may be more specific by handling both cases separately, as "Identity does not exist" errors can be safely ignored.
                echo 'Identity does not exist or Almefy service not reachable: '.$e->getMessage();
            }
        });
    }


    // will also create identity if it does not exist
    // if an identity was created outside of your system, you can set register_new_addresses to true
    // to create a new user in you system when an identity exists.
    //
    // You may re-enroll a user as often as you want => sending as many enrollment emails as you need.
    public function send_connect_device_email($email, $register_new_addresses) {
        
        // Should a local user be created if the almefy identity exists? 
        $send = $register_new_addresses;
        if($this->backend->get_user_by_mail($email)) {
            // definitely send the mail if the user already exists
            $send = true;
        } else if($register_new_addresses) {
            // Since we don't have a username, we are using the email address.
            // Maybe your system does not use usernames, or you use ids to identify.

            // If your system does not allow having no password set, generate
            // a strong, secure random password: https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php/31284266#31284266
            $password = "aksdhfioweur29ryjcvbaskldf3t"; // this should be random or NULL!
            $this->backend->create_user($email, $email, $password);
        }

        if($send) {
            try {
                $identifier = $email;
                $this->client->enrollIdentity($identifier, array(
                    // Tell Almefy to send the "Connect Device" email.
                    // In a later example we will send our own email template 
                    // or hot wo connect devices without sending emails at all.
                    'sendEmail' => true,
                    'sendEmailTo' => $email,
                ));

            } catch (\Almefy\Exception\TransportException $e) {
                // Show an error to the user.
                // Consider notifying an administrator about this failure.
                echo 'Could not connect to Almefy service: '.$e->getMessage();
            }
        }        
    }

    public function delete_device($device_id) {

        $user = $this->backend->get_current_user();
        $identity = $this->client->getIdentity($user->email);
        $devices = $identity->getTokens();

        // Check if the id belongs to this user before deleting!
        $device_found = false;
        foreach ($devices as $device) {
            if ($device_id === $device->getId()) {
                $device_found = true;
                break;
            }
        }

        if ($device_found) {
            try {
                $this->client->deleteToken($device_id);
            } catch (\Almefy\Exception\TransportException $e) {
                echo 'Identity does not exist or Almefy service not reachable: '.$e->getMessage();
            }
        } else {
            // Device does not belong to user.
            throw new Exception("Invalid request.");
        }
    }
}