<?php

// Load the key and secret from a safe environment
// You might want to provide some sort of configuration menu
// to your backend for administrators to easily set and replace keys.

require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;
$dotenv = Dotenv::createImmutable(__DIR__ . "/..");
$dotenv->load();

require_once "Backend.php";
$backend = new Backend();

///////////////////// 
// Use whatever method provided by the frame work to load your plugin / module / custom code
require_once "plugins/MyAlmefyPlugin.php";
$almefy = new MyAlmefyPlugin($backend);
////////////////////////////////////////////// 