<?php

// Include your Database and WhatsappInfo classes
require_once '../config/Database.php';
require_once '../models/WhatsappInfo.php';

// Initialize the Database and get the connection
$database = new Database();
$db = $database->connect();

// Check the database connection
if ($db === null) {
    echo "Failed to connect to the database.";
    exit;
}

// Initialize the WhatsappInfo class with the connection
$whatsappInfo = new WhatsappInfo($db);

// Set properties for posting data
$whatsappInfo->PhoneNumber = '1234567890';
$whatsappInfo->Message = 'Hello World';
$whatsappInfo->Timestamp = date('Y-m-d H:i:s');

// Insert data into the database
if ($whatsappInfo->postData()) {
    echo "Record created successfully.";
} else {
    echo "Failed to create record.";
}
